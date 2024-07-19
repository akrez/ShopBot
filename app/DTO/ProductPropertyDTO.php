<?php

namespace App\DTO;

class ProductPropertyDTO extends DTO
{
    public function __construct(
        public $property_key,
        public $property_value
    ) {
    }

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [
            'property_key' => ['required', 'max:32'],
            'property_value' => ['required', 'max:32'],
        ];
    }
}
