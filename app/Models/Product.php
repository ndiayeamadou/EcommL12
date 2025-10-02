<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'brand_id',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'is_featured',
        'status',
        'tags',
        'weight',
        'dimensions',
        'attributes',
        'variations',
        'rating',
        'reviews_count',
        'views_count',
        'sales_count',
        'meta_data',
        'featured_until',
    ];

    protected $casts = [
        'tags' => 'array',
        'dimensions' => 'array',
        'attributes' => 'array',
        'variations' => 'array',
        'meta_data' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'rating' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
    ];

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_colors')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where(function ($q) {
                        $q->whereNull('featured_until')
                          ->orWhere('featured_until', '>', now());
                    });
    }

    public function scopeInStock($query)
    {
        return $query->where('in_stock', true)
                    ->where(function ($q) {
                        $q->where('manage_stock', false)
                          ->orWhere('stock_quantity', '>', 0);
                    });
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
                    ->where('sale_price', '>', 0)
                    ->where('sale_price', '<', 'price');
    }

    public function scopeWithBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    // Accessors
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    public function getIsOnSaleAttribute()
    {
        //return $this->sale_price && $this->sale_price < $this->price;
        return $this->price > $this->sale_price && $this->sale_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }
        
        return '/images/placeholder-product.jpg';
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->display_price, 2) . ' F';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return number_format($this->price, 2) . ' F';
    }

    public function getImageGalleryAttribute()
    {
        return $this->images()->orderBy('sort_order')->get()->map(function ($image) {
            return asset('storage/' . $image->image_path);
        })->toArray();
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateStock($quantity, $operation = 'decrease')
    {
        if (!$this->manage_stock) {
            return;
        }

        if ($operation === 'decrease') {
            $this->decrement('stock_quantity', $quantity);
        } else {
            $this->increment('stock_quantity', $quantity);
        }

        $this->update(['in_stock' => $this->stock_quantity > 0]);
    }

    public function updateColorStock($colorId, $quantity, $operation = 'decrease')
    {
        $productColor = $this->colors()->where('color_id', $colorId)->first();
        
        if ($productColor) {
            if ($operation === 'decrease') {
                $newQuantity = max(0, $productColor->pivot->quantity - $quantity);
            } else {
                $newQuantity = $productColor->pivot->quantity + $quantity;
            }
            
            $this->colors()->updateExistingPivot($colorId, ['quantity' => $newQuantity]);
        }
    }

    // Route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}