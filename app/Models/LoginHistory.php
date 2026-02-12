<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'username',
        'email',
        'role',
        'ip_address',
        'user_agent',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
