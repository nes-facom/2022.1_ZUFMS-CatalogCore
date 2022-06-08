<?php

namespace App\Http\Controllers;

use App\Services\EmailSenderService;
use App\Helpers\JWTHelper;
use App\Helpers\ArrayHelper;
use App\Helpers\StringHelper;
use App\DTO\Input\TokenRequestDTO;
use App\DTO\Input\OtpTokenRequestDTO;
use App\DTO\Input\OtpRequestDTO;
use App\DTO\Input\ClientCredentialsTokenRequestDTO;
use App\DTO\DTOValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function token(Request $request) {
        $token_request;

        try {
            $token_request = TokenRequestDTO::fromRequest($request);
        } catch(DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        $requested_scopes = $token_request->scopes_array;

        if ($token_request->type == 'client_credentials') {
            try {
                $token_request = ClientCredentialsTokenRequestDTO::fromRequest($request);
            } catch(DTOValidationException $e) {
                return response()->json([
                    'errors' => $e->errors
                ], 400);
            }
    
            // Check client existence/credentials
            $client = DB::table('client')
                ->where('id', $token_request->client_id)
                ->first();

            if (is_null($client)) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ], 401);
            }

            $secret_match = Hash::check($token_request->client_secret, $client->secret);

            if (!$secret_match) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ], 401);
            }

            $client_avaliable_scopes = DB::table('client_inherited_scope')
                ->where('client_id', $client->id)
                ->whereIn('inherited_from_scope_name', $requested_scopes)
                ->pluck('inherited_scope_name')
                ->unique()
                ->toArray();

            // Store access_token
            $refresh_token = "";

            $access_token_scope = implode(' ', $client_avaliable_scopes);

            $ttl = config('jwt.ttl');
            $expires_in = strtotime('+' . $ttl . ' minutes');

            $access_token_jti = DB::table('access_token')
                ->insertGetId([
                    'sub_type' => 'client',
                    'refresh_token' => $refresh_token,
                    'expires_in' => date("Y-m-d h:m:s", $expires_in),
                    'scope' => $access_token_scope
                ], 'jti');

            $jwt = JWTHelper::generate([
                'exp' => $expires_in,
                'jti' => $access_token_jti,
                'sub_type' => 'client',
                'sub' => $client->id,
                'iss' => $client->id,
                'scope' => $access_token_scope
            ]);

            return response()->json([
                "access_token" => $jwt,
                "refresh_token" => $refresh_token,
                "token_type" => "bearer",
                "expires_in" => $expires_in
            ]);
        }


        if ($token_request->type == 'otp') {
            try {
                $token_request = OtpTokenRequestDTO::fromRequest($request);
            } catch(DTOValidationException $e) {
                return response()->json([
                    'errors' => $e->errors
                ], 400);
            }

            $jwt = $request->bearerToken();

            if (is_null($jwt) || !JWTHelper::validate($jwt)['valid']) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ], 401);
            }

            $access_token = JWTHelper::parse($jwt);

            $otp = DB::table('otp')
                ->where('value', '=', $token_request->otp)
                ->where('email', '=', $token_request->email)
                ->first();

            if (is_null($otp)) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ], 401);
            }

            DB::table('otp')
                ->where('value', '=', $otp->value)
                ->delete();

            $user = DB::table('user')
                ->where('email', '=', $otp->email)
                ->first();

            $user_avaliable_scopes = DB::table('user_inherited_scope')
                ->where('user_id', $user->id)
                ->whereIn('inherited_from_scope_name', $requested_scopes)
                ->pluck('inherited_scope_name')
                ->toArray();
                
            // Store access_token
            $refresh_token = "";

            $access_token_scope = implode(' ', $user_avaliable_scopes);

            $ttl = config('jwt.ttl');
            $expires_in =  strtotime('+' . $ttl . ' minutes');

            $access_token_jti = DB::table('access_token')
                ->insertGetId([
                    'sub_type' => 'user',
                    'refresh_token' => $refresh_token,
                    'expires_in' => date("Y-m-d h:m:s", $expires_in),
                    'scope' => $access_token_scope
                ], 'jti');

            $jwt = JWTHelper::generate([
                'exp' => $expires_in,
                'jti' => $access_token_jti,
                'sub_type' => 'user',
                'sub' => $user->id,
                'iss' => $access_token['payload']['sub'],
                'scope' => $access_token_scope
            ]);

            return response()->json([
                "access_token" => $jwt,
                "refresh_token" => $refresh_token,
                "token_type" => "bearer",
                "expires_in" => $expires_in
            ]);
        }
    }

    public function revoke(Request $request) {}

    public function otp(Request $request) {
        $otp_request;

        try {
            $otp_request = OtpRequestDTO::fromRequest($request);
        } catch(DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        $jwt = $request->bearerToken();

        if (is_null($jwt) || !JWTHelper::validate($jwt)['valid']) {
            return response()->json([
                'errors' => [
                    'code' => 6,
                    'title' => 'Credenciais inválidas',
                    'description' => 'Não foi possível autenticar o usuário'
                ]
            ], 401);
        }

        $access_token = JWTHelper::parse($jwt);

        $ttl = config('jwt.ttl');
        $expires_in =  strtotime('+' . $ttl . ' minutes');

        $client_callback_url = DB::table('client')
            ->where('id', '=', $access_token['payload']['sub'])
            ->pluck('callback_url')
            ->first();

        if ($otp_request->otp_method == 'email') {
            $otp_value = StringHelper::random();

            DB::table('otp')
                ->insert([
                    'value' => $otp_value,
                    'email' => $otp_request->email,
                    'state' => $otp_request->state,
                    'scope' => $otp_request->scope,
                    'expires_in' => date("Y-m-d h:m:s", $expires_in),
                    'requested_with_access_token' => $access_token['payload']['jti']
                ]);

            EmailSenderService::send(
                "Código: " . $otp_value . "<br />" .
                "Ou " . "<a href=\"http://localhost:3001/auth/cb?otp=" . $otp_value . "&state=" . $otp_request->state . "\">clique aqui</a>",
                "lima.barbosa@ufms.br",
                "Código de acesso ZUFMS"
            );

            return response()->json(null, 200);
        }
    }

    public function userinfo(Request $request) {
        $jwt = $request->bearerToken();

        if (is_null($jwt) || !JWTHelper::validate($jwt)['valid']) {
            return response()->json([
                'errors' => [
                    'code' => 6,
                    'title' => 'Credenciais inválidas',
                    'description' => 'Não foi possível autenticar o usuário'
                ]
            ], 401);
        }

        $access_token = JWTHelper::parse($jwt);

        if ($access_token['payload']['sub_type'] == 'user') {
            $user = DB::table('user')
                ->where('id', $access_token['payload']['sub'])
                ->first();

            return response()->json($user);
        }

        if ($access_token['payload']['sub_type'] == 'client') {
            $client = DB::table('client')
                ->where('id', $access_token['payload']['sub'])
                ->first();

            return response()->json($client);
        }

        response()->json([]);
    }
    
}
