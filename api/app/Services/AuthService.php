<?php

namespace App\Services;

use App\DTO\Input\AuthInputDTO;
use App\Models\User;

class AuthService
{
    private $emailSenderService;
    private $accessCodeService;

    public function __construct(EmailSenderService $emailSenderService, AccessCodeService $accessCodeService)
    {
        $this->emailSenderService = $emailSenderService;
        $this->accessCodeService = $accessCodeService;
    }

    public function sendPassword(AuthInputDTO $inputDTO){
        $user = $this->checkUserExists($inputDTO->getEmail());
        if($user == null){
            return response()->json([
                'error' => 'true',
                'message' => 'User not found'
            ], 400);
        }

        $password = $this->accessCodeService->getPassword();
        $user->update(['password' => $password]);

        $this->emailSenderService->send('send-access-code', $user->email, ['password'=>$password], 'Codigo de acesso');

        return response()->json([
            'error' => 'false',
            'message' => 'Email sent',
        ], 200);
    }

    public function sendAuthToken(AuthInputDTO $inputDTO){
        $user = $this->checkValidAuth($inputDTO->getEmail(), $inputDTO->getPassword());

        if($user == null){
            return response()->json([
                'error' => 'true',
                'message' => 'Wrong Email or Access Code'
            ], 400);
        }
        $user->update(['password' => bcrypt($inputDTO->getPassword())]);

        $credentials = ['email' => $inputDTO->getEmail(),
                        'password' => $inputDTO->getPassword()];
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    private function checkUserExists(string $email){
        return User::query()
            ->where('email', '=', $email)
            ->first();
    }

    private function checkValidAuth(string $email, string $password){
        return User::query()
            ->where('email', '=', $email)
            ->where('password', '=', $password)
            ->first();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
