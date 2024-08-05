<?php

namespace App\Support\MessageProcessors;

use App\Services\ApiService;
use App\Support\MessageProcessor;
use App\Support\TelegramApi;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CategoryMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        return Str::startsWith($this->message->message_text, static::CATEGORY_PREFIX);
    }

    public function process()
    {
        $category = Str::of($this->message->message_text)->chopStart(static::CATEGORY_PREFIX)->value();

        $jsonResponse = resolve(ApiService::class)->blogArray($this->bot->blog);
        $products = Arr::get($jsonResponse, 'products', []);
        $categories = collect($products)->pluck('product_tags')->flatten()->unique()->sort()->toArray();

        $categoryIsValid = ($category and in_array($category, $categories));

        if (! $categoryIsValid) {
            (new TelegramApi($this->bot))->sendMessage(
                $this->message->chat_id,
                'دستهلندی معتبر نیست',
                $this->getDefaultReplyMarkup() + [
                    'parse_mode' => 'MarkdownV2',
                ]
            );

            return;
        }

        $this->filterProducts(collect($products)->filter(function ($product) use ($category) {
            return in_array($category, $product['product_tags']);
        })->toArray());
    }
}
