<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductColor extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    // Relations
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    // Accessors
    public function getIsInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity > 0 && $this->quantity <= 5; // Seuil personnalisable
    }

    // Methods
    public function decrementStock($quantity = 1)
    {
        $this->decrement('quantity', $quantity);
        return $this;
    }

    public function incrementStock($quantity = 1)
    {
        $this->increment('quantity', $quantity);
        return $this;
    }

    public function setStock($quantity)
    {
        $this->update(['quantity' => max(0, $quantity)]);
        return $this;
    }
}
