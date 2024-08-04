<?php

namespace App\DTO;

class MessageDTO extends DTO
{
    public function __construct(
        public $id,
        public $bot_id,
        public $chat_id,
        public $message_text,
        public $message_json,
        public $processor = null
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [];
    }
}
