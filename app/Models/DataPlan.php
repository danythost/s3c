<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPlan extends Model
{
    protected $fillable = [
        'provider',
        'network',
        'code',
        'name',
        'provider_price',
        'selling_price',
        'validity',
        'is_active',
    ];

    protected $casts = [
        'provider_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
