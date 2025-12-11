<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'country_code',
        'shipping_charge',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'shipping_charge' => 'decimal:2',
    ];

    /**
     * Get the country associated with this shipping zone
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }
}
