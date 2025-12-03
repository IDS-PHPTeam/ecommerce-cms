<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'variant_id',
        'attribute_name',
        'attribute_value',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the variant that owns the attribute.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
