<?php

namespace App\Support\MessageProcessors;

use App\Enums\MessageProcessor\ReplyMarkupEnum;

class CartMessageProcessor extends MessageProcessor
{
    public function shouldProcess()
    {
        if ($this->message->message_text === ReplyMarkupEnum::CART->trans()) {
            return true;
        }

        return false;
    }
}
