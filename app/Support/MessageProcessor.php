<?php

namespace App\Support;

use App\Contracts\MessageProcessorContract;
use App\Models\Bot;
use App\Models\Message;
use Illuminate\Support\Arr;

class MessageProcessor implements MessageProcessorContract
{
    const CATEGORY_PREFIX = '🗂 | ';

    const CONTACT_US = '☎️ | ارتباط با ما';

    public function __construct(
        public Bot $bot,
        public Message $message,
        public array $response,
    ) {}

    public function shouldProcess()
    {
        return true;
    }

    public function process() {}

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
                        'media' => $productImage['contain_url'],
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
        $products = Arr::get($this->response, 'products', []);

        $categories = collect($products)->pluck('product_tags')
            ->flatten()
            ->unique()
            ->sort()
            ->toArray();

        $keyboard = collect($categories)->map(function ($tag) {
            return [
                'text' => static::CATEGORY_PREFIX.$tag,
            ];
        })->toArray();

        return [
            'reply_markup' => json_encode([
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'keyboard' => array_merge([
                    [
                        [
                            'text' => static::CONTACT_US,
                        ],
                    ],
                ], array_chunk($keyboard, 2)),
            ]),
        ];
    }
}
