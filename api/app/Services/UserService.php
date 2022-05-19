<?php

namespace App\Services;

use App\DTO\Input\UserInputDTO;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserService
{
    private $userRepository;

    public function
    __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function createUser(UserInputDTO $inputDTO){

        if(preg_match('/^[A-Za-z0-9._%+-]+@ufms.br$/', $inputDTO->getEmail()) == 0)
            throw new Exception('Email informado não é do domínio ufms.');

        $newUser = [
            'email' => $inputDTO->getEmail()
        ];
        return $this->userRepository->save($newUser);
    }

    public function deleteUser(UserInputDTO $inputDTO){
        return $this->userRepository->deleteByEmail($inputDTO->getEmail());
    }
}
