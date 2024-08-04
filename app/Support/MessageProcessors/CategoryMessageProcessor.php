<?php

namespace App\Support\MessageProcessors;

use App\Services\ApiService;
use App\Traits\MessageProcessorTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CategoryMessageProcessor extends MessageProcessor
{
    use MessageProcessorTrait;

    public function shouldProcess()
    {
        return Str::startsWith($this->message->message_text, '/'.static::PREFIX);
    }

    public function process()
    {
        $categoryId = Str::of($this->message->message_text)->chopStart('/'.static::PREFIX)->value();

        $categoryIdIsValid = ($categoryId and intval($categoryId) == strval($categoryId));
        if (! $categoryIdIsValid) {
            return;
        }

        $jsonResponse = resolve(ApiService::class)->blogArray($this->bot->blog);

        $category = Arr::get($jsonResponse, 'blog_categories.0.values.'.$categoryId);
        if (! $category) {
            return;
        }

        $apiProductsCategories = Arr::get($jsonResponse, 'products_categories', []);

        $filterProductIds = collect($apiProductsCategories)->where('values', [$category])->pluck('model_id')->toArray();

        $this->filterProducts($jsonResponse, $filterProductIds);
    }
}
