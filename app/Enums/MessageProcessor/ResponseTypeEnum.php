<?php

namespace App\Enums\MessageProcessor;

use App\Enums\Enum;

enum ResponseTypeEnum: string
{
    use Enum;

    case BOT = 'bot';
    case CONTACT = 'contact';
    case CART = 'cart';
    case DEFAULT = 'default';
}
