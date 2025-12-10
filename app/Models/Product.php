<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
        'description',
        'description_en',
        'description_ar',
        'featured_image',
        'product_type',
        'price',
        'sale_price',
        'track_stock',
        'stock_quantity',
        'stock_status',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'track_stock' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the categories for the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * Get the gallery items for the product.
     */
    public function gallery()
    {
        return $this->hasMany(ProductGallery::class)->orderBy('sort_order');
    }

    /**
     * Get the variants for the product (if variable product).
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }
}
