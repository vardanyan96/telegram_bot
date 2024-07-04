<?php

namespace App\Telegram;

use App\Models\TelegraphChat;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class TelegramWebhookHandler extends WebhookHandler
{
    public function start()
    {

        if ($this->message->contact()) {
            Log::info('Contact Info!');
            Log::info(print_r($this->message->contact(), true));
        }

        $this->chat->message("Для верификация надо отправить номер телефона!")->send();
    }

    public function getContact()
    {
        Log::info(print_r($this->data->get('id'), true));
        //  $this->reply('oyyyoyo');
//        $this->chat->message('fdsfdsdsf s')->send();
    }

    public function hi()
    {
        Log::info('bot hi');
    }

    protected function handleChatMessage(Stringable $text): void
    {

        if (!$this->chat->phone && !$this->isPhoneNumber($text)) {
            $this->chat->message("Неправильный номер телефона!")->send();
            return;
        }

        if (!$this->chat->phone && $this->isPhoneNumber($text)) {
            $this->chat->update([
                'phone' => $text,
            ]);

            $this->chat->message("Номер телефона верифицирован!")->send();
            return;
        }

        // проверяем есть номер у пользователя
        if (!$this->chat->phone) {
            $this->chat->message("Для верификация надо отправить номер телефона!")->send();
            return;
        }

        // сохраняем в дб
        $this->saveMessage($text);

        return;
    }

    private function saveMessage($message){
        $this->chat->messages()->create([
            'message' => $message
        ]);
    }

    /**
     * Проверяем номер телефона
     * @param string $phoneNumber
     * @return bool
     */
    private function isPhoneNumber(string $phoneNumber)
    {
        $phoneRegex = '/^\+7\s?\(\d{3}\)\s?\d{3}-\d{2}-\d{2}$/';

        if (preg_match($phoneRegex, $phoneNumber)) {
            return true;
        }

        return false;
    }
}
