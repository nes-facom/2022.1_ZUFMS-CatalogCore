<?php

namespace App\DTO\Input;

use App\DTO\DataTransferObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TokenRequestDTO extends DataTransferObject
{
    const available_types = ['otp', 'client_credentials'];
    public $scopes_array;

    private function __construct(
        public $type, 
        public $scope
    )
    {
        $this->scopes_array = explode(' ', $scope);
    }

    protected static function getValidationRules($rawInput) {
        return [
            'type' => ['required', Rule::in(self::available_types)],
            'scope' => 'required|string',
        ];
    }

    public static function fromArray(array $array) {
        return new TokenRequestDTO(
            $array['type'],
            $array['scope']
        );
    }
}


class OtpTokenRequestDTO extends TokenRequestDTO
{
    const available_otp_methods = ['email'];

    private function __construct(
        public $email,
        public $otp_method,
        public $otp
    ){}

    protected static function getValidationRules($rawInput) {
        $is_otp = Rule::requiredIf($rawInput['type'] == 'otp');

        return [
            'email' => [$is_otp, 'email', 'exists:user,email'],
            'otp_method' => [$is_otp, Rule::in(self::available_otp_methods)],
            'otp' => [$is_otp, 'string', 'exists:otp,value']
        ];
    }

    public static function fromArray(array $array) {
        return new OtpTokenRequestDTO(
            $array['email'],
            $array['otp_method'],
            $array['otp']
        );
    }
}

class ClientCredentialsTokenRequestDTO extends TokenRequestDTO
{
    private function __construct(
        public $client_id,
        public $client_secret,
    ){}

    public static function getValidationRules($rawInput) {
        $is_client_credentials = Rule::requiredIf($rawInput['type'] == 'client_credentials');

        return [
            'client_id' => [$is_client_credentials, 'uuid', 'exists:client,id'],
            'client_secret' => [$is_client_credentials, 'string'],
        ];
    }

    public static function fromArray(array $array) {
        return new ClientCredentialsTokenRequestDTO(
            $array['client_id'],
            $array['client_secret'],
        );
    }
}
