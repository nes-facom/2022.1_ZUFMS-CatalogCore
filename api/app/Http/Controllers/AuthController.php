<?php

namespace App\Http\Controllers;

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
                ->pluck('inherited_scope_name')
                ->unique()
                ->toArray();

            if (!ArrayHelper::all_in_array($requested_scopes, $client_avaliable_scopes)) {
                return response()->json([
                    'errors' => [
                        'code' => 1,
                        'title' => 'Permissões Insuficientes',
                        'description' => 'Você não possui as permissões necessárias para realizar esta operação',
                    ]
                ], 403);
            }

            // Store access_token
            $refresh_token = "";

            $access_token_scope_array = DB::table('client_inherited_scope')
                ->where('client_id', $client->id)
                ->whereIn('inherited_from_scope_name', $requested_scopes)
                ->pluck('inherited_scope_name')
                ->unique()
                ->toArray();
            
            $access_token_scope = implode(' ', $access_token_scope_array);

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

            // TODO: Implement OTP retrieval and verification
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

            // Check requested scopes availability
            $user_avaliable_scopes = DB::table('user_inherited_scope')
                ->where('user_id', $user->id)
                ->pluck('inherited_scope_name')
                ->toArray();

            if (!ArrayHelper::all_in_array($requested_scopes, $user_avaliable_scopes)) {
                return response()->json([
                    'errors' => [
                        'code' => 1,
                        'title' => 'Permissões Insuficientes',
                        'description' => 'Você não possui as permissões necessárias para realizar esta operação',
                    ]
                ], 403);
            }

            // Store access_token
            $refresh_token = "";

            $access_token_scope_array = DB::table('user_inherited_scope')
                ->where('user_id', $user->id)
                ->whereIn('inherited_from_scope_name', $requested_scopes)
                ->pluck('inherited_scope_name')
                ->unique()
                ->toArray();
        
            $access_token_scope = implode(' ', $access_token_scope_array);

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
                    'scope' => $access_token_scope,
                    'requested_with_access_token' => $access_token['payload']['jti']
                ]);

            // TODO: Send e-mail with $client_callback_url+$otp and $otp_value

            return response()->json(null, 200);
        }
    }
}
