<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'product_id',
        'description',
        'sku',
        'price',
        'sale_price',
        'track_stock',
        'stock_quantity',
        'stock_status',
        'image',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'track_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attributes for the variant.
     */
    public function attributes()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'variant_id');
    }
}
