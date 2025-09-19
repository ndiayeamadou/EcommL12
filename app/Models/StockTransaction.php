<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sale_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reason',
        'reference'
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after' => 'integer'
    ];

    // Relations
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    // Scopes
    public function scopeSales($query)
    {
        return $query->where('type', 'sale');
    }

    public function scopeRestocks($query)
    {
        return $query->where('type', 'restock');
    }

    // Utilitaires
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'sale' => 'red',
            'restock' => 'green',
            'adjustment' => 'blue',
            'return' => 'yellow',
            default => 'gray'
        };
    }
}
