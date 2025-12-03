<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'rate' => 'decimal:6',
    ];

    /**
     * Get the source currency
     */
    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    /**
     * Get the target currency
     */
    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
