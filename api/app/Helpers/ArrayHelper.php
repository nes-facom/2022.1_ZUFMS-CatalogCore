<?php

namespace App\Helpers;

class ArrayHelper {
    public static function all_in_array($needles, $haystack) {
        return count(array_intersect($needles, $haystack)) == count($needles);
    }     

    public static function array_pick(array $array, array $keys) {
        return array_intersect_key($array, array_flip($keys));
    }

    public static function array_omit(array $array, array $keys) {
        return array_intersect_key($array, array_flip(array_diff(array_keys($array), $keys)));
    }
}