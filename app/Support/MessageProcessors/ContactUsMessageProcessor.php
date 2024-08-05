<?php

namespace App\Support\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Services\ApiService;
use App\Support\MessageProcessor;
use App\Support\TelegramApi;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;

class ContactUsMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CONTACT_US->trans()) {
            return true;
        }

        return false;
    }

    public function process()
    {
        $text = [];

        $jsonResponse = resolve(ApiService::class)->blogArray($this->bot->blog);

        $contacts = Arr::get($jsonResponse, 'contacts', []);

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
