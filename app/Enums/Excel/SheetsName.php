<?php

namespace App\Enums\Excel;

use App\Enums\Enum;

enum SheetsName: string
{
    use Enum;

    case PRODUCT = 'Product';
    case PRODUCT_TAG = 'Tag';
    case PRODUCT_PROPERTY = 'Property';
}
