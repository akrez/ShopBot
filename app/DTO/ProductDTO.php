<?php

namespace App\DTO;

use App\Enums\Product\ProductStatus;
use App\Models\Blog;
use App\Rules\BotCommandRule;
use Illuminate\Validation\Rule;

class ProductDTO extends DTO
{
    public Blog $blog;

    public ?int $id = null;

    public function __construct(
        public $code,
        public $name,
        public $product_status,
        public $product_order
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, $this->blog, $this->id);
    }

    public static function getRules(bool $isStore, Blog $blog, ?int $id = null)
    {
        $uniqueRule = Rule::unique('products', 'code')->where('blog_id', $blog->id);
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($id);
        }

        return [
            'name' => ['required', 'max:64'],
            'code' => ['required', 'max:32', new BotCommandRule, $uniqueRule],
            'product_status' => [Rule::in(ProductStatus::values())],
            'product_order' => ['nullable', 'numeric'],
        ];
    }
}
