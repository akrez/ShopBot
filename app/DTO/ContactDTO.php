<?php

namespace App\DTO;

use App\Enums\Contact\ContactType;
use Illuminate\Validation\Rule;

class ContactDTO extends DTO
{
    public function __construct(
        public $contact_type,
        public $contact_key,
        public $contact_value,
        public $contact_link,
        public $contact_order
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        return [
            'contact_type' => ['nullable', Rule::in(ContactType::values())],
            'contact_key' => ['required', 'max:255'],
            'contact_value' => ['required', 'max:1023'],
            'contact_link' => ['nullable'],
            'contact_order' => ['nullable', 'numeric'],
        ];
    }
}
