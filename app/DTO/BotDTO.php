<?php

namespace App\DTO;

use App\Models\Blog;
use Illuminate\Validation\Rule;

class BotDTO extends DTO
{
    public Blog $blog;

    public ?int $id = null;

    public function __construct(
        public $token
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, $this->blog, $this->id);
    }

    public static function getRules(bool $isStore, Blog $blog, ?int $id = null)
    {
        $uniqueRule = Rule::unique('bots', 'token')->where('blog_id', $blog->id);
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($id);
        }

        return [
            'token' => ['required', 'max:64', $uniqueRule],
        ];
    }
}
