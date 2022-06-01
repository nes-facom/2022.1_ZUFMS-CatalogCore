<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;
use Illuminate\Http\Request;

class CreateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $email
    )
    { }

    protected static function getValidationRules($rawInput) {        
        return [
            'email' => 'email',
        ];
    }

    public static function fromArray(array $array) {
        return new CreateUserRequestDTO(
            $array['email']
        );
    }
}