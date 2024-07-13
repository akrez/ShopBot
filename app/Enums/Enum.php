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

        //
        return collect(self::cases())->pluck('value', 'name')->map(function ($item, $key) use ($translates) {
            if (isset($translates[$item])) {
                return $translates[$item];
            }
            if (Lang::has($item)) {
                return __($item);
            }

            return $key;
        })->toArray();
    }

    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
