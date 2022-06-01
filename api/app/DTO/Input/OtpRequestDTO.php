<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OtpRequestDTO extends DataTransferObject
{
    const available_otp_methods = ['email'];

    private function __construct(
        public $otp_method, 
        public $email,
        public $state,
        public $scope
    )
    { }

    protected static function getValidationRules($rawInput) {        
        $is_email = Rule::requiredIf($rawInput['otp_method'] == 'email');

        return [
            'otp_method' => ['required', Rule::in(self::available_otp_methods)],
            'email' => [$is_email, 'email', 'exists:user,email'],
            'state' => 'string',
            'scope' => 'string'
        ];
    }

    public static function fromArray(array $array) {
        return new OtpRequestDTO(
            $array['otp_method'],
            $array['email'],
            $array['state'],
            $array['scope']
        );
    }
}