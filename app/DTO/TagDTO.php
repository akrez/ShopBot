<?php

namespace App\DTO;

class TagDTO extends DTO
{
    public function __construct(
        public $name
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [
            'name' => ['required', 'max:32'],
        ];
    }
}
