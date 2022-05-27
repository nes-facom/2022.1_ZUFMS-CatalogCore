<?php

namespace App\Helpers;

class ArrayHelper {
    public static function all_in_array($needles, $haystack) {
        return count(array_intersect($needles, $haystack)) == count($needles);
    }     
}