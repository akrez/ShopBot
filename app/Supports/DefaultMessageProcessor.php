<?php

namespace App\Supports;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Models\Bot;
use App\Models\Message;
use App\Services\TelegramApiService;

class DefaultMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Bot $bot, protected Message $message)
    {
    }

    public function isProcessor()
    {
        return true;
    }

    public function sendResponse()
    {
    }

    public function getDefaultReplyMarkup()
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => ReplyMarkupEnum::CART,
                        ],
                    ],
                    [
                        [
                            'text' => ReplyMarkupEnum::CONTACT_US,
                        ],
                    ],
                    [
                        [
                            'text' => ReplyMarkupEnum::REQUEST_CONTACT,
                            'request_contact' => true,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ];
    }
}
