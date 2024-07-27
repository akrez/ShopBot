<?php

namespace App\Enums\MessageProcessor;

use App\Enums\Enum;

enum ReplyMarkupEnum: string
{
    use Enum;

    public static function translates()
    {
        return [
            'request_contact' => '🔐 | ورود به وسیله اشتراک گزاری شماره تلفن همراه',
            'categories' => '🗂 | دسته‌بندی‌ها',
            'cart' => '🛒 | مشاهده سبد خرید',
            'contact_us' => '☎️ | ارتباط با ما',
        ];
    }

    case REQUEST_CONTACT = 'request_contact';
    case CATEGORIES = 'categories';
    case CART = 'cart';
    case CONTACT_US = 'contact_us';
}
