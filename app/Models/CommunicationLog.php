<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $fillable = [
        'user_id',
        'channel',
        'recipient',
        'message',
        'status',
        'provider_response',
    ];

    protected $casts = [
        'provider_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
