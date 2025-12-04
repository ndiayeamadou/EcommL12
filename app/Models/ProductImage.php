<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    // Relations
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        //return asset('storage/' . $this->image_path); // not found in prod
        return asset('myapp/storage/app/public/' . $this->image_path);
    }

    // Methods
    public function setAsPrimary()
    {
        // Remove primary status from other images of this product
        ProductImage::where('product_id', $this->product_id)
                    ->where('id', '!=', $this->id)
                    ->update(['is_primary' => false]);
        
        // Set this image as primary
        $this->update(['is_primary' => true]);
    }
}