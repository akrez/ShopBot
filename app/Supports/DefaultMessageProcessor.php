<?php

namespace App\Supports;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Enums\MessageProcessor\ResponseTypeEnum;
use App\Models\Message;

class DefaultMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Message $message) {}

    public function getResponseType()
    {
        return ResponseTypeEnum::DEFAULT->value;
    }

    public function sendResponse() {}

    public function getDefaultReplyMarkup()
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => ReplyMarkupEnum::CONTACT,
                            'request_contact' => true,
                        ],
                    ],
                    [
                        [
                            'text' => ReplyMarkupEnum::CART,
                        ],
                    ],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ];
    }
}
