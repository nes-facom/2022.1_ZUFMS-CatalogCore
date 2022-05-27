<?php

namespace App\Http\Controllers;

use App\Services\JWTService;
use App\Helpers\ArrayHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController
{
    public function token(Request $request) {
        $available_types = ['otp', 'client_credentials'];
        $available_otp_methods = ['email'];

        $input = $request->all();

        $is_otp = Rule::requiredIf($input['type'] == 'otp');
        $is_client_credentials = Rule::requiredIf($input['type'] == 'client_credentials');

        $validator = Validator::make($input, [
            // default values
            'type' => ['required', Rule::in($available_types)],
            'scope' => 'required|string',

            // "type": "otp"
            'email' => [$is_otp, 'email', 'exists:user,email'],
            'otp_method' => [$is_otp, Rule::in($available_otp_methods)],
            'otp' => [$is_otp, 'string', 'exists:otp,value'],

            // "type": "client_credentials"
            'client_id' => [$is_client_credentials, 'uuid', 'exists:client,id'],
            'client_secret' => [$is_client_credentials, 'string'],
        ]);

        if ($validator->fails()) {
            $errors = array_map(fn (string $description) => [
                'code' => 2,
                'title' => 'Dado inválido',
                'description' => $description
            ],  $validator->errors()->all());

            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $validated_input = $validator->safe();

        $requested_scopes = explode(' ', $validated_input['scope']);

        if ($validated_input['type'] == 'client_credentials') {

            // Check client existence/credentials
            $client = DB::table('client')
                ->where('id', $validated_input['client_id'])
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

            $secret_match = Hash::check($validated_input->client_secret, $client->secret);

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
                        'description' => 'Você não possui as permissão para realizar esta operação',
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

            $access_token_jit = DB::table('access_token')
                ->insertGetId([
                    'sub_type' => 'client',
                    'refresh_token' => $refresh_token,
                    'expires_in' => date("Y-m-d h:m:s", $expires_in),
                    'scope' => $access_token_scope
                ], 'jti');

            $jwt = JWTService::generate([
                'exp' => $expires_in,
                'jit' => $access_token_jit,
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


        if ($validated_input['type'] == 'otp') {

            // TODO: Check if provided access_token has
            //       scope = "client.auth:otp"

  

            $jwt = $request->bearerToken();

            if (is_null($jwt) || !JWTService::validate($jwt)['valid']) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ], 401);
            }

            $access_token = JWTService::parse($jwt);

            // TODO: Implement OTP retrieval and verification
            $otp = DB::table('otp')
                ->where('value', '=', $validated_input['otp'])
                ->where('email', '=', $validated_input['email'])
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
                        'description' => 'Você não possui as permissão para realizar esta operação',
                    ]
                ], 403);
            }

            // Store access_token
            $refresh_token = "";

            $ttl = config('jwt.ttl');
            $expires_in =  strtotime('+' . $ttl . ' minutes');

            $access_token_jit = DB::table('access_token')
                ->insertGetId([
                    'sub_type' => 'user',
                    'refresh_token' => $refresh_token,
                    'expires_in' => date("Y-m-d h:m:s", $expires_in)
                ], 'jti');

            $jwt = JWTService::generate([
                'exp' => $expires_in,
                'jit' => $access_token_jit,
                'sub_type' => 'user',
                'sub' => $user->id,
                'iss' => $access_token['payload']->sub,
                'scope' => implode(' ', $requested_scopes)
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
        $available_otp_methods = ["email"];

        $input = $request->all();

        $is_email = Rule::requiredIf($input['otp_method'] == 'email');

        $validator = Validator::make($input, [
            'otp_method' => ['required', Rule::in($available_otp_methods)],
            'email' => [$is_email, 'email', 'exists:user,email'],
            'state' => 'string',
        ]);

        if ($validator->fails()) {
            $errors = array_map(fn (string $description) => [
                'code' => 2,
                'title' => 'Dado inválido',
                'description' => $description
            ],  $validator->errors()->all());

            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $validated_input = $validator->safe();

        // TODO: Check if provided access_token has
        //       scope = "client.auth:otp"
        $access_token = [
            'jti' => '4bc7dba9-46cc-41c2-802a-dcb5a76120c7',
            'scope' => 'client.auth:otp',
            'sub' => '4bc7dba9-46cc-41c2-802a-dcb5a76120c7'
        ];

        $client_callback_url = DB::table('client')
            ->where('id', '=', $access_token['sub'])
            ->pluck('callback_url')
            ->first();

        if ($validated_input['otp_method'] == 'email') {
            // TODO: Implement OTP generation logic (maybe directly on database)
            $otp_value = "";

            DB::table('otp')
                ->insert([
                    'value' => $otp_value,
                    'email' => $validated_input['email'],
                    'state' => $validated_input['state'],
                    'requested_with_access_token' => $access_token['jti']
                ]);

            // TODO: Send e-mail with $client_callback_url+$otp and $otp_value

            return response()->json(null, 200);
        }
    }
}
