<?php

namespace App\Support\MessageProcessors;

use App\Support\MessageProcessor;
use App\Support\TelegramApi;
use Illuminate\Support\Arr;

class ContactUsMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        if ($this->message->message_text === static::CONTACT_US) {
            return true;
        }

        return false;
    }

    public function process()
    {
        $text = [];

        $contacts = Arr::get($this->response, 'contacts', []);

        foreach ($contacts as $contactUs) {
            $text[] = '***'.$contactUs['contact_key'].'*** '.$contactUs['contact_value'];
        }

        return (new TelegramApi($this->bot))->sendMessage(
            $this->message->chat_id,
            implode("\n", $text),
            $this->getDefaultReplyMarkup() + [
                'parse_mode' => 'MarkdownV2',
            ]
        );
    }
}
