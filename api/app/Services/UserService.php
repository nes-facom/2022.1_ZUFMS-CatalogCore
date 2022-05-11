<?php

namespace App\Services;

use App\DTO\Input\UserInputDTO;
use App\Models\User;

class UserService
{
    public function createUser(UserInputDTO $inputDTO){
        User::query()->insert([
            'email' => $inputDTO->getEmail()
        ]);

        return response()->json([
            'status'=>'created',
            'error'=> false
        ], 201);
    }

    public function deleteUser(UserInputDTO $inputDTO){
        User::query()
            ->where('email', '=', $inputDTO->getEmail())
            ->delete();

        return response()->json([
            'status'=>'deleted',
            'error'=> false
        ], 202);
    }
}
