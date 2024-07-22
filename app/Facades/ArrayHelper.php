<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed iexplode($delimiters, $string, $limit = PHP_INT_MAX)
 * @method static mixed filterArray($array, $doFilter = true, $checkUnique = true, $doTrim = true)
 * @method static mixed templatedArray($template = [], $values = [], $const = [])
 * @method static mixed trimRecursive($array)
 */
class ArrayHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ArrayHelper';
    }
}
