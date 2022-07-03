<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;

class UpdateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $id,
        public $email,
        public $allowed_scopes
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'id' => 'required|string|exists:user,id',
            'email' => 'email',
            'allowed_scopes' => 'array',
            'allowed_scopes.*' => 'string|exists:scope,name'
        ];
    }

    public static function fromArray(array $array) {
        return new UpdateUserRequestDTO(
            $array['id'],
            $array['email'] ?? null,
            $array['allowed_scopes'] ?? null,
        );
    }
}
