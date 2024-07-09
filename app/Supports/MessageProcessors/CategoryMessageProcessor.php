<?php

namespace App\Supports\MessageProcessors;

use App\Services\ShopApi;
use App\Services\TelegramApiService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CategoryMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        return Str::startsWith($this->message->message_text, '/'.CategoriesMessageProcessor::PREFIX);
    }

    public function process()
    {
        $id = Str::of($this->message->message_text)->chopStart('/'.CategoriesMessageProcessor::PREFIX)->value();

        $idIsValid = ($id and intval($id) == strval($id));
        if (! $idIsValid) {
            return;
        }

        $jsonResponse = resolve(ShopApi::class)->json();

        $category = Arr::get($jsonResponse, 'blog_categories.0.values.'.$id);
        if (! $category) {
            return;
        }

        $apiProductsCategories = Arr::get($jsonResponse, 'products_categories', []);

        $needleProductIds = collect($apiProductsCategories)->where('values', [$category])->pluck('model_id')->toArray();

        $allProducts = Arr::get($jsonResponse, 'products', []);
        $allProductsImages = Arr::get($jsonResponse, 'products_images', []);
        $allProductsProperties = Arr::get($jsonResponse, 'products_properties', []);

        $needleProducts = collect($allProducts)->filter(function ($item) use ($needleProductIds) {
            return in_array($item['id'], $needleProductIds);
        })->values()->all();

        foreach ($needleProducts as $needleProduct) {
            $caption = ['***'.$needleProduct['title'].'***'];

            $needleProductProperties = collect($allProductsProperties)->filter(function ($item) use ($needleProduct) {
                return intval($item['model_id']) === intval($needleProduct['id']);
            })->values();
            if ($needleProductProperties) {
                $caption[] = '';
                foreach ($needleProductProperties as $needleProductProperty) {
                    if ($needleProductProperty['values']) {
                        $caption[] = '***'.$needleProductProperty['key'].'***'.' '.implode(', ', $needleProductProperty['values']);
                    }
                }
            }

            $needleProductImages = collect($allProductsImages)->filter(function ($item) use ($needleProduct) {
                return intval($item['model_id']) === intval($needleProduct['id']);
            })->values()->first();

            if ($needleProductImages and $needleProductImages['names']) {
                $medias = [];
                foreach ($needleProductImages['names'] as $needleProductImageName) {
                    $medias[$needleProductImageName] = [
                        'type' => 'photo',
                        'media' => 'https://gallery.akrezing.ir/'.$needleProductImageName,
                    ];
                    if ($caption) {
                        $medias[$needleProductImageName]['caption'] = implode("\n\n", $caption);
                        $medias[$needleProductImageName]['parse_mode'] = 'MarkdownV2';
                        $caption = null;
                    }
                }

                (new TelegramApiService($this->bot))->sendMediaGroup(
                    $this->message->chat_id,
                    $medias,
                    $this->getDefaultReplyMarkup()
                );
            } else {

                (new TelegramApiService($this->bot))->sendMessage(
                    $this->message->chat_id,
                    implode("\n\n", $caption),
                    $this->getDefaultReplyMarkup() + [
                        'parse_mode' => 'HTML',
                    ]
                );
            }
        }
    }
}
