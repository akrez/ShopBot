<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Enums\MessageProcessor\ResponseTypeEnum;
use App\Supports\DefaultMessageProcessor;

class CartMessageProcessor extends DefaultMessageProcessor
{
    public function getResponseType()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CART->value) {
            return ResponseTypeEnum::CART->value;
        }
    }
}
