<?php

namespace App\Support\MessageProcessors;

use App\Services\ShopApi;
use App\Services\TelegramApiService;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SearchMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function process()
    {
        $productTitleFilter = Str::trim($this->message->message_text);
        if (! $productTitleFilter) {
            (new TelegramApiService($this->bot))->sendMessage(
                $this->message->chat_id,
                'لطفا قسمتی از عنوان محصول مورد نظر خود را وارد کنید',
                $this->getDefaultReplyMarkup() + [
                    'parse_mode' => 'MarkdownV2',
                ]
            );

            return;
        }

        $jsonResponse = resolve(ShopApi::class)->json();

        $apiProducts = Arr::get($jsonResponse, 'products', []);

        $filterProductIds = collect($apiProducts)->filter(function ($item) use ($productTitleFilter) {
            return Str::contains($item['title'], $productTitleFilter, true);
        })->pluck('id')->toArray();

        if ($filterProductIds) {
            $this->filterProcess($jsonResponse, $filterProductIds);
        } else {
            (new TelegramApiService($this->bot))->sendMessage(
                $this->message->chat_id,
                'محصول با عنوانی که شامل ***'.$productTitleFilter.'*** باشد یافت نشد',
                $this->getDefaultReplyMarkup() + [
                    'parse_mode' => 'MarkdownV2',
                ]
            );
        }
    }
}
