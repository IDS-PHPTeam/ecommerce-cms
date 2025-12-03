<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'driver_id',
        'value',
        'status',
        'settlement_date',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'settlement_date' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
