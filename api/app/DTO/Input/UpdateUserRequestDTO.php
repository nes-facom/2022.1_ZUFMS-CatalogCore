<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;

class UpdateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $id,
        public $email,
        public $scope_id
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'id' => 'required|string|exists:user,id',
            'email' => 'nullable|email|unique:user,email',
            'scope_id' => 'nullable|array',
            'scope_id.*' => 'integer|exists:scope,id'
        ];
    }

    public static function fromArray(array $array) {
        return new UpdateUserRequestDTO(
            $array['id'],
            $array['email'],
            $array['scope_id']
        );
    }
}
