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
            'id' => 'string|exists:user,id',
            'email' => 'required_if:type,string|email|unique:user,email',
            'scope' => 'integer|required_if:type,integer|exists:scope,id'
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
