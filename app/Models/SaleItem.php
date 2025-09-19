<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'discount_per_item',
        'subtotal',
        'product_snapshot'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_per_item' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'product_snapshot' => 'array'
    ];

    // Relations
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Méthodes utilitaires
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 2) . ' FCFA';
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2) . ' FCFA';
    }

    public function getTotalDiscountAttribute(): float
    {
        return $this->discount_per_item * $this->quantity;
    }

    // Boot pour calculer automatiquement le subtotal
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($saleItem) {
            $saleItem->subtotal = ($saleItem->unit_price * $saleItem->quantity) - ($saleItem->discount_per_item * $saleItem->quantity);
        });
    }
}
