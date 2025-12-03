<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attribute values for this attribute.
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}

