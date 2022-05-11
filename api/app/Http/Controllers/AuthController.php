<?php

namespace App\Http\Controllers;

use App\DTO\Input\AuthInputDTO;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(Request $request){
        $inputDTO = AuthInputDTO::fromRequest($request);

        if($inputDTO->getPassword() == null)
            return $this->authService->sendPassword($inputDTO);

        return $this->authService->sendAuthToken($inputDTO);
    }

    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
