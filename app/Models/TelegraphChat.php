<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegraphChat extends \DefStudio\Telegraph\Models\TelegraphChat
{

    CONST STATUS_ACTIVE = '1';
    CONST STATUS_INACTIVE = '0';

    public static function statuses(){
        return [
            self::STATUS_INACTIVE => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
        ];
    }

    protected $fillable = [
        'chat_id',
        'name',
        'status',
        'phone'
    ];


    public function messages(): HasMany{
        return $this->hasMany(TelegramChatMessage::class,'telegram_chat_id','id');
    }
}
