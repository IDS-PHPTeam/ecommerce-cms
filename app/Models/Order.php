<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'location',
        'driver_id',
        'status',
        'subtotal',
        'delivery_price',
        'total',
        'concurrency',
        'priority',
        'feedback',
        'rating',
        'order_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_price' => 'decimal:2',
        'total' => 'decimal:2',
        'order_date' => 'datetime',
        'rating' => 'integer',
        'concurrency' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
