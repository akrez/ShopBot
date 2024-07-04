<?php

namespace App\Enums\MessageProcessor;

use App\Enums\Enum;

enum ReplyMarkupEnum: string
{
    use Enum;

    case REQUEST_CONTACT = '🔐 ورود به وسیله اشتراک گزاری شماره تلفن همراه';
    case CART = '🛒 مشاهده سبد خرید';
    case CONTACT_US = '📞 ارتباط با ما';
}
