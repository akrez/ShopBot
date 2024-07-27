<?php

namespace App\Traits;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Support\TelegramApi;
use Illuminate\Support\Arr;

trait MessageProcessorTrait
{
    const PREFIX = 'category_';

    public function filterProcess($jsonResponse, $filterProductIds = null)
    {
        $allProducts = Arr::get($jsonResponse, 'products', []);
        $allProductsImages = Arr::get($jsonResponse, 'products_images', []);
        $allProductsProperties = Arr::get($jsonResponse, 'products_properties', []);

        $needleProducts = collect($allProducts)->filter(function ($item) use ($filterProductIds) {
            return $filterProductIds === null ? true : in_array($item['id'], $filterProductIds);
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
                        $medias[$needleProductImageName]['caption'] = implode("\n", $caption);
                        $medias[$needleProductImageName]['parse_mode'] = 'MarkdownV2';
                        $caption = null;
                    }
                }

                (new TelegramApi($this->bot))->sendMediaGroup(
                    $this->message->chat_id,
                    $medias,
                    $this->getDefaultReplyMarkup() + [
                        'parse_mode' => 'MarkdownV2',
                    ]
                );
            } else {

                (new TelegramApi($this->bot))->sendMessage(
                    $this->message->chat_id,
                    implode("\n\n", $caption),
                    $this->getDefaultReplyMarkup() + [
                        'parse_mode' => 'MarkdownV2',
                    ]
                );
            }
        }
    }

    public function getDefaultReplyMarkup()
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => ReplyMarkupEnum::CONTACT_US->trans(),
                        ],
                        [
                            'text' => ReplyMarkupEnum::CATEGORIES->trans(),
                        ],
                    ],
                    /*
                    [
                        [
                            'text' => ReplyMarkupEnum::CART->trans(),
                        ],
                    ],
                    [
                        [
                            'text' => ReplyMarkupEnum::REQUEST_CONTACT->trans(),
                            'request_contact' => true,
                        ],
                    ],
                    */
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ];
    }
}
