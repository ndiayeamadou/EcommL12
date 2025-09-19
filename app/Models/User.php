<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'email',
        'password',
        'customer_number',
        'type',
        'username',
        'gender',
        'birth_date',
        'ncin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'suspended_at' => 'datetime',
        'last_login_at' => 'datetime',
    ]; */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'suspended_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }
    
    // Constantes pour les types d'utilisateurs
    const TYPE_CUSTOMER = 0;
    const TYPE_ADMIN = 3;
    const TYPE_PROVIDER = 5;

    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Accessors pour faciliter l'accès aux informations
    public function getPhoneAttribute()
    {
        return $this->detail->phone ?? null;
    }

    public function getAddressAttribute()
    {
        return $this->detail->address ?? null;
    }

    public function getCityAttribute()
    {
        return $this->detail->city ?? null;
    }

    public function getPostalCodeAttribute()
    {
        return $this->detail->postal_code ?? null;
    }

    public function getCountryAttribute()
    {
        return $this->detail->country ?? null;
    }

    // Méthode pour mettre à jour ou créer les détails
    public function updateDetails(array $details)
    {
        if ($this->detail) {
            $this->detail->update($details);
        } else {
            $this->detail()->create($details);
        }
        
        return $this->detail;
    }

    // Scopes pour filtrer par type
    public function scopeCustomers($query)
    {
        return $query->where('type', self::TYPE_CUSTOMER);
    }

    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    public function scopeProviders($query)
    {
        return $query->where('type', self::TYPE_PROVIDER);
    }

    // Méthodes de vérification de type
    public function isCustomer(): bool
    {
        return $this->type === self::TYPE_CUSTOMER;
    }

    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isProvider(): bool
    {
        return $this->type === self::TYPE_PROVIDER;
    }

    // Génération automatique du numéro client
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->customer_number)) {
                $user->customer_number = 'CUST' . str_pad(static::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
