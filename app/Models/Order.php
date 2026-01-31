<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'amount',
        'type',
        'status',
        'provider',
        'details',
        'api_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'details' => 'array',
        'api_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
