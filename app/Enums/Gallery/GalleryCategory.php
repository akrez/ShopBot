<?php

namespace App\Enums\Gallery;

use App\Enums\Enum;

enum GalleryCategory: string
{
    use Enum;

    case PRODUCT_IMAGE = 'product_image';
    case BLOG_LOGO = 'blog_logo';
}
