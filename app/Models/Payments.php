<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer type
 * @property float amount
 * @property string data
 */
class Payments extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /**
     * Тип платежа текст
     */
    const TYPE = [
        1 => 'Списание',    // Списание
        2 => 'Пополнение',     // Пополнение
    ];

    /**
     * Тип платежа
     */
    const TYPE_TEXT = [
        'writeOff' => 1,    // Списание
        'deposit' => 2,     // Пополнение
    ];

    /**
     * Мутируем тип при получении
     *
     * @param $value
     * @return object
     */
    public function getTypeAttribute($value)
    {
        return (object)[
            'id' => $value,
            'text' => self::TYPE[$value],
        ];
    }
}
