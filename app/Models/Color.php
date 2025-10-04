<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relations
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_colors')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function productColors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }

    // Accessors
    public function getFormattedHexCodeAttribute(): string
    {
        return '#' . ltrim($this->hex_code, '#');
    }

    public function getContrastTextColorAttribute(): string
    {
        // Calculer la luminosité de la couleur pour déterminer si le texte doit être blanc ou noir
        $hex = ltrim($this->hex_code, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Formule de luminosité
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    // Methods
    public function getTotalStock(): int
    {
        return $this->productColors()->sum('quantity');
    }

    public function getProductsCount(): int
    {
        return $this->products()->count();
    }

    public function isUsedInProducts(): bool
    {
        return $this->products()->exists();
    }

    // Mutators
    public function setHexCodeAttribute($value)
    {
        // S'assurer que le code hex commence par #
        $this->attributes['hex_code'] = '#' . ltrim($value, '#');
    }

    // Validation des couleurs courantes
    public static function getCommonColors(): array
    {
        return [
            'Rouge' => '#FF0000',
            'Bleu' => '#0000FF',
            'Vert' => '#00FF00',
            'Jaune' => '#FFFF00',
            'Orange' => '#FFA500',
            'Violet' => '#800080',
            'Rose' => '#FFC0CB',
            'Marron' => '#A52A2A',
            'Gris' => '#808080',
            'Noir' => '#000000',
            'Blanc' => '#FFFFFF',
        ];
    }
}
