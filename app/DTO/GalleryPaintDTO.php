<?php

namespace App\DTO;

use App\Services\GalleryService;
use Closure;
use Illuminate\Validation\Rule;
use Intervention\Image\Encoders\AutoEncoder;

class GalleryPaintDTO extends DTO
{
    const WHMQ_REGEX_PATTERN = '/[A-Za-z0-9\-\.\_]/';

    public function __construct(
        public $whmq,
        public int $defaultWidth,
        public int $defaultHeight,
        protected $width = null,
        protected $height = null,
        protected $mode = null,
        protected $quality = null
    ) {
        [
            $this->width,
            $this->height,
            $this->mode,
            $this->quality,
        ] = explode('_', $whmq) + array_fill(0, 4, null);
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getQuality()
    {
        return $this->quality ?? AutoEncoder::DEFAULT_QUALITY;
    }

    public function rules(bool $isStore = true)
    {
        return static::getRules($isStore, $this->defaultWidth, $this->defaultHeight);
    }

    public static function getRules(bool $isStore, $defaultWidth, $defaultHeight)
    {
        return [
            'width' => ['nullable', 'integer', 'min:1', 'max:'.($defaultWidth * 2)],
            'height' => ['nullable', 'integer', 'min:1', 'max:'.($defaultHeight * 2)],
            'mode' => ['nullable', Rule::in(GalleryService::VALID_MODES)],
            'quality' => ['nullable', 'integer', 'min:1', 'max:100'],
            'whmq' => [
                'regex:'.static::WHMQ_REGEX_PATTERN,
                function (string $attribute, mixed $value, Closure $fail) {
                    if (count(explode($value, '_')) >= 5) {
                        $fail(__('validation.regex', [
                            'attribute' => __('validation.attributes.'.$attribute),
                        ]));
                    }
                },
            ],
        ];
    }
}
