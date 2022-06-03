<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;

class UpdateUserRequestDTO extends DataTransferObject
{

    private function __construct(
        public $id,
        public $email,
        public $scope
    )
    { }

    protected static function getValidationRules($rawInput) {
        return [
            'id' => 'required|string|exists:user,id',
            'email' => 'nullable|email|unique:user,email',
            'scope' => 'nullable|integer|exists:scope,id'
        ];
    }

    public static function fromArray(array $array) {
        return new UpdateUserRequestDTO(
            $array['id'],
            $array['email'],
            $array['scope']
        );
    }
}
