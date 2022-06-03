<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;
use Illuminate\Http\Request;

class CreateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $email,
        public $scope
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'email' => 'required|email|unique:user,email',
            'scope' => 'required|integer|exists:scope,id'
        ];
    }

    public static function fromArray(array $array) {
        return new CreateUserRequestDTO(
            $array['email'],
            $array['scope']
        );
    }
}
