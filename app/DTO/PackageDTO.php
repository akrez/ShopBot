<?php

namespace App\DTO;

use App\Enums\Package\PackageStatus;
use App\Models\Blog;
use App\Services\ColorService;
use Illuminate\Validation\Rule;

class PackageDTO extends DTO
{
    public Blog $blog;

    public function __construct(
        public $price,
        public $package_status,
        public $color_id,
        public $guaranty,
        public $description
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, $this->blog);
    }

    public static function getRules(bool $isStore, Blog $blog)
    {
        return [
            'price' => ['required', 'numeric'],
            'color_id' => ['nullable', Rule::in(array_keys(app(ColorService::class)->getLatestBlogColorsIdNameArray($blog)))],
            'guaranty' => ['nullable', 'string', 'max:256'],
            'description' => ['nullable', 'string', 'max:2048'],
            'package_status' => ['required', Rule::in(PackageStatus::values())],
        ];
    }
}
