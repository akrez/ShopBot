<?php

namespace App\Enums\Excel;

use App\Enums\Enum;

enum SheetName: string
{
    use Enum;

    public static function translates()
    {
        return [
            'product' => __('Products'),
            'product_tag' => __('Tags'),
            'product_property' => __('Properties'),
            'contact' => __('Contacts'),
        ];
    }

    case PRODUCT = 'product';
    case PRODUCT_TAG = 'product_tag';
    case PRODUCT_PROPERTY = 'product_property';
    case CONTACT = 'contact';
}
