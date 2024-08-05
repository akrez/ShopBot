<?php

namespace App\Support\MessageProcessors;

use App\Services\ApiService;
use App\Support\MessageProcessor;
use App\Support\TelegramApi;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;

class CategoriesMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        return $this->message->message_text === static::CATEGORIES;
    }

    public function process()
    {
        $jsonResponse = resolve(ApiService::class)->blogArray($this->bot->blog);

        $categories = collect(Arr::get($jsonResponse, 'products', []))->pluck('product_tags')->flatten()->unique()->sort()->toArray();

        (new TelegramApi($this->bot))->sendMessage(
            $this->message->chat_id,
            'لطفا از کیبورد انتخاب کنید',
            $this->getReplyMarkup($categories)
        );
    }

    public function getReplyMarkup($categories)
    {
        $keyboard = collect($categories)->map(function ($tag) {
            return [
                'text' => static::CATEGORY_PREFIX.$tag,
            ];
        })->toArray();

        return [
            'reply_markup' => json_encode([
                'keyboard' => array_chunk($keyboard, 3),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ];
    }
}
