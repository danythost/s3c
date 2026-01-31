<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirtimeControl extends Model
{
    protected $fillable = [
        'network',
        'min_amount',
        'max_amount',
        'commission_percentage',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
