<?php

namespace App\Services;

use App\Helpers\ArrayHelper;
use App\Helpers\EncodingHelper;

class JWTService {
    public static function has_scopes($jwt, ...$scopes) {
        $jwt_scope = self::parse($jwt)['payload']['scope'];
        $jwt_scope_array = explode(' ', $jwt_scope);

        return ArrayHelper::all_in_array($scopes, $jwt_scope_array);
    }

    public static function generate($claims) {
        $alg = config('jwt.algo');

        $header = json_encode([
            'typ' => 'JWT', 
            'alg' => $alg
        ]);

        $ttl = config('jwt.ttl');
        $iat = time();

        $default_claims = [
            'iat' => $iat,
            'exp' => strtotime('+' . $ttl . ' minutes', $iat),
        ];

        $payload = json_encode(array_merge(
            $default_claims,
            $claims,
        ));

        $encoded_header = EncodingHelper::base64url_encode($header);
        $encoded_payload = EncodingHelper::base64url_encode($payload);
        
        $signature_encoded = self::generate_signature($encoded_header, $encoded_payload);
        
        $jwt = "$encoded_header.$encoded_payload.$signature_encoded";
        
        return $jwt;
    }

    private static function generate_signature($encoded_header, $encoded_payload) {
        $secret = config('jwt.secret');

        $signature = hash_hmac('sha256', "$encoded_header.$encoded_payload", $secret, true);
        $signature_encoded = EncodingHelper::base64url_encode($signature);

        return $signature_encoded;
    }

    public static function parse($jwt) {
        $token_parts = explode('.', $jwt);
        $header_json = base64_decode($token_parts[0]);
        $payload_json = base64_decode($token_parts[1]);
        $signature = $token_parts[2];

        $header = json_decode($header_json);
        $payload = json_decode($payload_json);

        return [
            'header' => $header,
            'payload' => $payload,
            'signature' => $signature
        ];
    }

    public static function validate($jwt) {
        $parsed_jwt = self::parse($jwt);

        $is_token_expired = ($parsed_jwt['payload']->exp - time()) < 0;
    
        if ($is_token_expired) {
            return [
                'valid' => false, 
                'reason' => 'Token expirated'
            ];
        }

        $signature_encoded = self::generate_signature(
            EncodingHelper::base64url_encode(json_encode($parsed_jwt['header'])), 
            EncodingHelper::base64url_encode(json_encode($parsed_jwt['payload'])));
    
        $is_signature_valid = ($signature_encoded === $parsed_jwt['signature']);
        
        if (!$is_signature_valid) {
            return [
                'valid' => false, 
                'reason' => 'Invalid token signature'
            ];
        }

        return [
            'valid' => true
        ];
    }
}
