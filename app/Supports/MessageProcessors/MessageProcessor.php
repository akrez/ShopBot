<?php

namespace App\Supports\MessageProcessors;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Models\Bot;
use App\Models\Message;

class MessageProcessor implements MessageProcessorContract
{
    public function __construct(public Bot $bot, public Message $message)
    {
    }

    public function shouldProcess()
    {
        return true;
    }

    public function process()
    {
    }

    public function getDefaultReplyMarkup()
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => ReplyMarkupEnum::CONTACT_US,
                        ],
                        [
                            'text' => ReplyMarkupEnum::CATEGORIES,
                        ],
                    ],
                    /*
                    [
                        [
                            'text' => ReplyMarkupEnum::CART,
                        ],
                    ],
                    [
                        [
                            'text' => ReplyMarkupEnum::REQUEST_CONTACT,
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
