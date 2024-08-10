<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class GalleryDTO extends DTO
{
    public function __construct(
        public ?UploadedFile $file,
        public $gallery_order,
        public $is_selected,
    ) {}

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore);
    }

    public static function getRules(bool $isStore)
    {
        $commonRules = [
            'gallery_order' => ['nullable', 'numeric'],
            'is_selected' => ['nullable', 'boolean'],
        ];

        $imageRules = [
            'file' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
        ];

        if ($isStore) {
            return $imageRules + $commonRules;
        }

        return $commonRules;
    }
}
