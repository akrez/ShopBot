<?php

namespace App\Supports\MessageProcessors;

use App\Services\TelegramApiService;

class SearchMessageProcessor extends MessageProcessor
{
    public function process()
    {
        return (new TelegramApiService($this->bot))->sendMessage(
            $this->message->chat_id,
            '<br>جستجو برای</br> '.$this->message->message_text,
            $this->getDefaultReplyMarkup() + [
                'parse_mode' => 'HTML',
            ]
        );
    }
}
