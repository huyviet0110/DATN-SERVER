<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory,
        Notifiable,
        SoftDeletes;

    const ERR_VALIDATION        = 'E1001';
    const ERR_REGISTER          = 'E1002';
    const ERR_SEND_VERIFY_EMAIL = 'E1003';
    const ERR_FROM_SERVER       = 'E1004';
    const ERR_ACCOUNT_INCORRECT = 'E1005';
    const ERR_NOT_VERIFY_EMAIL  = 'E1006';
    const ERR_LOGOUT            = 'E1007';
    const ERR_GET_PROFILE       = 'E1008';
    const ERR_UPDATE_PROFILE    = 'E1023';
    const ERR_ORDER_NOT_EXISTS  = 'E1025';
    const ERR_USER              = 'E1030';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'gender',
        'birth_date',
        'phone_number',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
