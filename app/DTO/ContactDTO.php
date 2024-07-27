<?php

namespace App\DTO;

use App\Enums\Contact\ContactType;
use App\Models\Blog;
use Illuminate\Validation\Rule;

class ContactDTO extends DTO
{
    public Blog $blog;

    public ?int $id = null;

    public function __construct(
        public $contact_type,
        public $contact_key,
        public $contact_value,
        public $contact_link,
        public $contact_order
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, [
            'blog_id' => $this->blog->id,
            'contact_key' => $this->contact_key,
        ], $this->id);
    }

    public static function getRules(bool $isStore, $uniquenessArray, $id)
    {
        $uniqueRule = Rule::unique('contacts');
        foreach ($uniquenessArray as $attribute => $value) {
            $uniqueRule = $uniqueRule->where($attribute, $value);
        }
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($id);
        }

        return [
            'contact_type' => ['nullable', Rule::in(ContactType::values())],
            'contact_key' => ['bail', 'required', 'max:255', $uniqueRule],
            'contact_value' => ['required', 'max:1023'],
            'contact_link' => ['nullable'],
            'contact_order' => ['nullable', 'numeric'],
        ];
    }
}
