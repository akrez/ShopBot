<?php

namespace App\DTO;

use App\Enums\Product\ProductStatus;
use App\Models\Blog;
use App\Rules\BotCommandRule;
use Illuminate\Validation\Rule;

class ProductDTO extends DTO
{
    public Blog $blog;

    public ?int $id;

    public function __construct(
        public $code,
        public $name,
        public $product_status,
    ) {}

    public function rules(bool $isStore = true)
    {
        $uniqueRule = Rule::unique('products', 'code')->where('blog_id', $this->blog->id);
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($this->id);
        }

        return [
            'name' => ['required', 'max:64'],
            'code' => ['required', 'max:32', new BotCommandRule, $uniqueRule],
            'product_status' => [Rule::in(ProductStatus::values())],
        ];
    }
}
