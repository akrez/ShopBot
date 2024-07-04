<?php

namespace App\Supports\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;
use App\Supports\DefaultMessageProcessor;

class CartMessageProcessor extends DefaultMessageProcessor
{
    public function isProcessor()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CART->value) {
            return true;
        }

        return false;
    }
}
