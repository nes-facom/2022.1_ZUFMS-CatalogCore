<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;

class CreateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $email,
        public $allowed_scopes
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'email' => 'required|email|unique:user,email',
            'allowed_scopes' => 'array',
            'allowed_scopes.*' => 'string|exists:scope,name'
        ];
    }

    public static function fromArray(array $array) {
        return new CreateUserRequestDTO(
            $array['email'],
            $array['allowed_scopes'] ?? null
        );
    }
}
