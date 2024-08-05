<?php

namespace App\Traits;

use App\Support\TelegramApi;

trait MessageProcessorTrait
{
    const CATEGORY_PREFIX = '📂 | ';

    const CATEGORIES = '🗂 | دسته‌بندی‌ها';

    const CONTACT_US = '☎️ | ارتباط با ما';

    public function filterProducts($products)
    {
        foreach ($products as $product) {
            $caption = ['***'.$product['name'].'***'];

            if ($product['product_properties']) {
                $caption[] = '';
                foreach ($product['product_properties'] as $productProperty) {
                    if ($productProperty['property_values']) {
                        $caption[] = '***'.$productProperty['property_key'].'***'.' '.implode(', ', $productProperty['property_values']);
                    }
                }
            }

            if ($product['images']) {
                $medias = [];
                foreach ($product['images'] as $productImageKey => $productImage) {
                    $medias[$productImageKey] = [
                        'type' => 'photo',
                        'media' => $productImage['url'],
                    ];
                    if ($caption) {
                        $medias[$productImageKey]['caption'] = implode("\n", $caption);
                        $medias[$productImageKey]['parse_mode'] = 'MarkdownV2';
                        $caption = [];
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
                    implode("\n", $caption),
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
                            'text' => static::CONTACT_US,
                        ],
                        [
                            'text' => static::CATEGORIES,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ];
    }
}
