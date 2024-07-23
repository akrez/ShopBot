<?php

namespace App\DTO;

use App\Support\ArrayHelper;

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
            'tag_name' => ['required', 'max:'.ArrayHelper::MAX_LENGTH],
        ];
    }
}
