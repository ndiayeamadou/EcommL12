<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'legal_name', 'registration_number', 'tax_number',
        'email', 'phone', 'website', 'logo', 'description',
        'address', 'city', 'state', 'postal_code', 'country',
        'parent_id', 'type', 'founded_date', 'employee_count',
        'annual_revenue', 'industry', 'is_active', 'is_verified',
        'contact_person_name', 'contact_person_phone',
        'contact_person_email', 'contact_person_position',
        'settings', 'social_links', 'sort_order',
    ];

    protected $casts = [
        'founded_date' => 'date',
        'annual_revenue' => 'decimal:2',
        'employee_count' => 'integer',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'settings' => 'array',
        'social_links' => 'array',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });

        static::updating(function ($company) {
            if ($company->isDirty('name') && empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    public function subsidiaries(): HasMany
    {
        return $this->hasMany(Company::class, 'parent_id')->orderBy('sort_order');
    }

    public function allSubsidiaries(): HasMany
    {
        return $this->subsidiaries()->with('allSubsidiaries');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeHolding($query)
    {
        return $query->where('type', 'holding');
    }

    public function scopeSubsidiary($query)
    {
        return $query->where('type', 'subsidiary');
    }

    public function scopeParentCompanies($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildCompanies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=6366f1&color=fff';
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'holding' => 'Société Mère',
            'subsidiary' => 'Filiale',
            'branch' => 'Succursale',
            default => 'Autre',
        };
    }

    public function hasParent(): bool
    {
        return !is_null($this->parent_id);
    }

    public function hasSubsidiaries(): bool
    {
        return $this->subsidiaries()->exists();
    }

    public function isHolding(): bool
    {
        return $this->type === 'holding';
    }

    public function isSubsidiary(): bool
    {
        return $this->type === 'subsidiary';
    }

    public function getLevel(): int
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    public function getTotalSubsidiaries(): int
    {
        $count = $this->subsidiaries()->count();
        
        foreach ($this->subsidiaries as $subsidiary) {
            $count += $subsidiary->getTotalSubsidiaries();
        }

        return $count;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}