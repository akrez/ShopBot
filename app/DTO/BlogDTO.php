<?php

namespace App\DTO;

use App\Enums\Blog\BlogStatus;
use Illuminate\Validation\Rule;

class BlogDTO extends DTO
{
    public function __construct(
        public $name,
        public $short_description,
        public $description,
        public $blog_status
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore = true)
    {
        return [
            'name' => 'required|max:64',
            'short_description' => 'required|max:120',
            'description' => 'required|max:512',
            'blog_status' => [Rule::in(BlogStatus::values())],
        ];
    }
}
