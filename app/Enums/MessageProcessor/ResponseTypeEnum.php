<?php

namespace App\Enums\MessageProcessor;

use App\Enums\Enum;

enum ResponseTypeEnum: string
{
    use Enum;

    case BOT = 'bot';
    case REQUEST_CONTACT = 'request_contact';
    case CART = 'cart';
    case DEFAULT = 'default';
}
