<?php

namespace App\Supports\MessageProcessors;

use App\Services\TelegramApiService;

class SearchMessageProcessor extends MessageProcessor
{
    public function process()
    {
        (new TelegramApiService($this->bot))->sendMessage(
            $this->message->chat_id,
            'جستجو برای ***'.$this->message->message_text.'***',
            $this->getDefaultReplyMarkup() + [
                'parse_mode' => 'MarkdownV2',
            ]
        );
    }
}
