<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class A2CRequest extends Model
{
    use HasFactory;

    protected $table = 'a2c_requests';

    protected $fillable = [
        'user_id',
        'network',
        'amount',
        'payable',
        'phone',
        'sitephone',
        'reference',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payable' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
