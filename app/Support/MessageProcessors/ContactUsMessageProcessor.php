<?php

namespace App\Support\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Services\ShopApi;
use App\Support\TelegramApi;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;

class ContactUsMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CONTACT_US->value) {
            return true;
        }

        return false;
    }

    public function process()
    {
        $text = [];

        $jsonResponse = resolve(ShopApi::class)->json();

        $contacts = Arr::get($jsonResponse, 'contacts', []);

        foreach ($contacts as $contactUs) {
            $text[] = '***'.$contactUs['title'].'*** '.$contactUs['content'];
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
