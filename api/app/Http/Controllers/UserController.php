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
        $isCreated = $this->userService->createUser($inputDTO);
        return $isCreated
                    ? $this->createResponse('created', false, 201)
                    : $this->createResponse('failed', true, 409);
    }

    public function deleteUser(Request $request){
        $inputDTO = UserInputDTO::fromRequest($request);
        $isDeleted = $this->userService->deleteUser($inputDTO);
        return $isDeleted
            ? $this->createResponse('deleted', false, 200)
            : $this->createResponse('failed', true, 409);
    }

    private function createResponse(string $status, bool $error, int $statusCode){
        return response()->json([
            'status'=> $status,
            'error'=> $error
        ], $statusCode);
    }
}
