<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin Builder
 * @property integer id
 * @property string name
 * @property string email
 * @property string phone
 * @property string password
 * @property Payments Payments
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Мутируем пароль
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Связь с платежами
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Payments()
    {
        return $this->hasMany(Payments::class, 'user_id', 'id');
    }
}
