<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'attribute_id',
        'value',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the attribute that owns this value.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}

