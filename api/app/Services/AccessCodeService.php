<?php

namespace App\Services;

class AccessCodeService
{
    private const ALFA_NUMERIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CODE_LENGHT = 5;

    public function getPassword(){
        return substr(str_shuffle(self::ALFA_NUMERIC),1,self::CODE_LENGHT);
    }
}
