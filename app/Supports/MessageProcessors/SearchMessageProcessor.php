<?php

namespace App\Supports\MessageProcessors;

use App\Services\ShopApi;
use App\Services\TelegramApiService;
use App\Traits\MessageProcessorTrait;
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
        }

        $jsonResponse = resolve(ShopApi::class)->json();

        $filterProductIds = collect($jsonResponse)->filter(function ($item) use ($productTitleFilter) {
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
