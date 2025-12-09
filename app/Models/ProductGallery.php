<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory, HasAuditFields;

    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id',
        'media_path',
        'media_type',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the gallery item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
