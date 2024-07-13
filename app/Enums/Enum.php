<?php

namespace App\Enums;

use Illuminate\Support\Facades\Lang;

trait Enum
{
    public static function translates()
    {
        return [];
    }

    public static function translatedValues(): array
    {
        $translates = self::translates();

        return collect(self::cases())->pluck('value', 'value')->map(function ($value) use ($translates) {
            if (isset($translates[$value])) {
                return $translates[$value];
            }
            if (Lang::has($value)) {
                return __($value);
            }

            return $value;
        })->toArray();
    }

    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
