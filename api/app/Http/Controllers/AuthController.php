<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController
{
    public function __construct() {}

    public function token(Request $request) {
        $available_types = ['otp', 'client_credentials'];
        $available_otp_methods = ['email'];

        $is_otp = Rule::requiredIf($input['type'] == 'otp');
        $is_client_credentials = Rule::requiredIf($input['type'] == 'client_credentials');

        $input = $request->all();

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
            ]);
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
                ]);
            }

            // TODO: Implement password verification (bcrypt)
            $password_match = false;

            if (!$password_match) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ]);
            }

            // TODO: Create function that checks if user/client
            //       has all scopes provided
            $client_avaliable_scopes = DB::table('client_allowed_scope')
                ->where('client_id', $client->id);

            foreach ($requested_scopes as &$requested_scope) {
                if (!in_array($requested_scope, $client_avaliable_scopes)) {
                    return response()->json([
                        'errors' => [
                            'code' => 1,
                            'title' => 'Permissões Insuficientes',
                            'description' => 'Você não possui as permissão para realizar esta operação'
                        ]
                    ]);
                }
            }

            // Store access_token

            $refresh_token = "";
            $expires_in = 0;

            $access_token = DB::table('access_token')
                ->insert([
                    'sub_type' => 'client',
                    'refresh_token' => $refresh_token,
                    'expires_in' => $expires_in
                ]);

            $jti = $access_token->jti;

            // TODO: Generate JWT (using $jti)
            $jwt = "";

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

            // TODO: Implement OTP retrieval and verification
            $otp = DB::table('otp')
                ->leftJoin('user', 'user.id', '=', 'otp.user_id')
                ->where('value', '=', $validated_input['otp'])
                ->where('email', '=', $validated_input['email']);

            if (is_null($otp)) {
                return response()->json([
                    'errors' => [
                        'code' => 6,
                        'title' => 'Credenciais inválidas',
                        'description' => 'Não foi possível autenticar o usuário'
                    ]
                ]);
            }

            DB::table('otp')
                ->where('value', '=', $otp->value)
                ->delete();

            // Check requested scopes availability
            // TODO: Create function that checks if user/client
            //       has all scopes provided
            $user_avaliable_scopes = DB::table('user_allowed_scope')
                ->where('user_id', $user->id);

            foreach ($requested_scopes as &$requested_scope) {
                if (!in_array($requested_scope, $user_avaliable_scopes)) {
                    return response()->json([
                        'errors' => [
                            'code' => 1,
                            'title' => 'Permissões Insuficientes',
                            'description' => 'Você não possui as permissão para realizar esta operação'
                        ]
                    ]);
                }
            }

            // Store access_token

            $refresh_token = "";
            $expires_in = 0;

            $access_token = DB::table('access_token')
                ->insert([
                    'sub_type' => 'user',
                    'refresh_token' => $refresh_token,
                    'expires_in' => $expires_in
                ]);

            $jti = $access_token->jti;

            // TODO: Generate JWT (using $jti)
            $jwt = "";

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
            'otp_method' => ['required', Rule::in($avaliableTermColumns)],
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
            ]);
        }

        $validated_input = $validator->safe();

        // TODO: Check if provided access_token has
        //       scope = "client.auth:otp"
        $access_token = [
            'jti' => '',
            'scope' => 'client.auth:otp'
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
