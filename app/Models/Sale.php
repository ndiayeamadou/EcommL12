<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_number',
        'customer_name',
        'customer_email', 
        'customer_phone',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'notes',
        'payment_details',
        'cashier_name',
        'user_id',
        'sale_date'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'payment_details' => 'array',
        'sale_date' => 'datetime'
    ];

    protected $dates = ['sale_date', 'deleted_at'];

    // Relations
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('sale_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Méthodes utilitaires
    public static function generateSaleNumber(): string
    {
        $year = date('Y');
        $lastSale = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSale ? (int) substr($lastSale->sale_number, -4) + 1 : 1;
        
        return 'POS-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' FCFA';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }

    public function getPaymentMethodIconAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'banknotes',
            'card' => 'credit-card',
            'mobile_money' => 'device-phone-mobile',
            'bank_transfer' => 'building-library',
            default => 'currency-dollar'
        };
    }

    // Boot method pour auto-générer le numéro
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (!$sale->sale_number) {
                $sale->sale_number = self::generateSaleNumber();
            }
            if (!$sale->sale_date) {
                $sale->sale_date = now();
            }
        });
    }
}
