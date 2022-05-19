<?php

namespace App\Services;

use App\DTO\Input\AuthInputDTO;
use App\Repository\UserRepository;

class AuthService
{
    private $emailSenderService;
    private $accessCodeService;
    private $userRepository;

    public function __construct(EmailSenderService $emailSenderService, AccessCodeService $accessCodeService,
                                UserRepository $userRepository)
    {
        $this->emailSenderService = $emailSenderService;
        $this->accessCodeService = $accessCodeService;
        $this->userRepository = $userRepository;
    }

    public function sendPassword(AuthInputDTO $inputDTO){
        $user = $this->userRepository->findByEmail($inputDTO->getEmail());
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
        $user = $this->userRepository->findByEmailAndPassword($inputDTO->getEmail(), $inputDTO->getPassword());

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

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
