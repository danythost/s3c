<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return true;
    }

    public function getRoleAttribute()
    {
        return 'admin';
    }

    public function getIsActiveAttribute()
    {
        return true;
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id')->whereRaw('1 = 0'); // Dummy relation
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'id')->whereRaw('1 = 0'); // Dummy relation
    }

    public function getLastLoginAtAttribute()
    {
        return null;
    }
}
