<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_default',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get exchange rates where this currency is the source
     */
    public function exchangeRatesFrom()
    {
        return $this->hasMany(CurrencyExchangeRate::class, 'from_currency_id');
    }

    /**
     * Get exchange rates where this currency is the target
     */
    public function exchangeRatesTo()
    {
        return $this->hasMany(CurrencyExchangeRate::class, 'to_currency_id');
    }

    /**
     * Get the exchange rate to another currency
     */
    public function getExchangeRateTo($toCurrencyId)
    {
        $rate = CurrencyExchangeRate::where('from_currency_id', $this->id)
            ->where('to_currency_id', $toCurrencyId)
            ->first();
        
        return $rate ? $rate->rate : null;
    }

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }
}
