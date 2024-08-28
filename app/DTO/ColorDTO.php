<?php

namespace App\DTO;

use App\Models\Blog;
use Illuminate\Validation\Rule;

class ColorDTO extends DTO
{
    public Blog $blog;

    public ?int $id = null;

    public function __construct(
        public $code,
        public $name
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, [
            'blog_id' => $this->blog->id,
            'code' => $this->code,
        ], $this->id);
    }

    public static function getRules(bool $isStore, $uniquenessArray, $id)
    {
        $uniqueRule = Rule::unique('colors');
        foreach ($uniquenessArray as $attribute => $value) {
            $uniqueRule = $uniqueRule->where($attribute, $value);
        }
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($id);
        }

        return [
            'code' => ['required', 'max:16', 'regex:/^#[A-F0-9]{6}$/', $uniqueRule],
            'name' => ['required', 'max:31'],
        ];
    }
}
