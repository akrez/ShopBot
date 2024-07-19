<?php

namespace App\DTO;

use App\Services\ProductPropertyService;

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
            'property_key' => ['required', 'max:' . ProductPropertyService::MAX_LENGTH],
            'property_value' => ['required', 'max:' . ProductPropertyService::MAX_LENGTH],
        ];
    }
}
