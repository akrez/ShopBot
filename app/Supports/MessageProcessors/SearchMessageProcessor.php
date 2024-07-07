<?php

namespace App\Supports\MessageProcessors;

use App\Services\TelegramApiService;

class SearchMessageProcessor extends MessageProcessor
{
    public function process()
    {
        return (new TelegramApiService($this->bot))->sendMessage(
            $this->message->chat_id,
            $this->message->id,
            $this->getDefaultReplyMarkup()
        );
    }
}
