<?php

namespace App\DTO;

use App\Support\ArrayHelper;

class ProductPropertyDTO extends DTO
{
    public function __construct(
        public $property_key,
        public $property_value
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [
            'property_key' => ['required', 'max:'.ArrayHelper::MAX_LENGTH],
            'property_value' => ['required', 'max:'.ArrayHelper::MAX_LENGTH],
        ];
    }
}
