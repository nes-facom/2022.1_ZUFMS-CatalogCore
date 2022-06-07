<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;
use Illuminate\Http\Request;

class CreateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $email,
        public $scope_id
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'email' => 'required|email|unique:user,email',
            'scope_id' => 'required|array',
            'scope_id.*' => 'required|integer|exists:scope,id'
        ];
    }

    public static function fromArray(array $array) {
        return new CreateUserRequestDTO(
            $array['email'],
            $array['scope_id']
        );
    }
}
