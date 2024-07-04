<?php

namespace App\Supports;

use App\Contracts\MessageProcessorContract;
use App\Enums\MessageProcessorResponseTypeEnum;
use App\Models\Message;

class DefaultMessageProcessor implements MessageProcessorContract
{
    public function __construct(protected Message $message) {}

    public function getResponseType()
    {
        return MessageProcessorResponseTypeEnum::DEFAULT->value;
    }

    public function sendResponse() {}

    public function getDefaultReplyMarkup()
    {
        return [
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        [
                            'text' => 'ورود به وسیله اشتراک گزاری شماره تلفن همراه',
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
