<?php

namespace App\Http\Controllers;

use App\DTO\Input\UserInputDTO;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    /**
     * @param $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(Request $request){
        $inputDTO = UserInputDTO::fromRequest($request);
        return $this->userService->createUser($inputDTO);
    }

    public function deleteUser(Request $request){
        $inputDTO = UserInputDTO::fromRequest($request);
        return $this->userService->deleteUser($inputDTO);
    }
}
