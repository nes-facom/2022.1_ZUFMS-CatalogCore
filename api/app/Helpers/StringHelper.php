<?php

namespace App\Helpers;

class StringHelper {
    public static function random() {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        return substr(str_shuffle($permitted_chars), 0, 5);
    }     
}