<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
        'description',
        'description_en',
        'description_ar',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the products for the category (many-to-many).
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
}
