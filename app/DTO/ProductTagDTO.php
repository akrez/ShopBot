<?php

namespace App\DTO;

use App\Services\ProductTagService;

class ProductTagDTO extends DTO
{
    public function __construct(
        public $tag_name
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [
            'tag_name' => ['required', 'max:'.ProductTagService::TAG_NAME_MAX_LENGTH],
        ];
    }
}
