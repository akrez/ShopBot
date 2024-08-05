<?php

namespace App\Support\MessageProcessors;

use App\Services\ApiService;
use App\Support\MessageProcessor;
use App\Support\TelegramApi;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SearchMessageProcessor extends MessageProcessor
{
    public function process()
    {
        $productTitleFilter = Str::trim($this->message->message_text);
        if (! $productTitleFilter) {
            (new TelegramApi($this->bot))->sendMessage(
                $this->message->chat_id,
                'لطفا قسمتی از عنوان محصول مورد نظر خود را وارد کنید',
                $this->getDefaultReplyMarkup() + [
                    'parse_mode' => 'MarkdownV2',
                ]
            );

            return;
        }

        $jsonResponse = resolve(ApiService::class)->blogArray($this->bot->blog);

        $apiProducts = Arr::get($jsonResponse, 'products', []);

        $filteredProducts = collect($apiProducts)->filter(function ($item) use ($productTitleFilter) {
            return Str::contains($item['name'], $productTitleFilter, true);
        });

        if ($filteredProducts->count()) {
            $this->filterProducts($filteredProducts);
        } else {
            (new TelegramApi($this->bot))->sendMessage(
                $this->message->chat_id,
                'محصول با عنوانی که شامل ***'.$productTitleFilter.'*** باشد یافت نشد',
                $this->getDefaultReplyMarkup() + [
                    'parse_mode' => 'MarkdownV2',
                ]
            );
        }
    }
}
