<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public function findByEmail(string $email){
        return User::query()
            ->where('email', '=', $email)
            ->first();
    }

    public function findByEmailAndPassword(string $email, string $password){
        return User::query()
            ->where('email', '=', $email)
            ->where('password', '=', $password)
            ->first();
    }

    public function save(array $user){
        return User::query()->insert($user);
    }

    public function deleteByEmail(string $email){
        $user = User::query()
            ->where('email', '=', $email)
            ->first();

        return $user->delete();
    }
}
