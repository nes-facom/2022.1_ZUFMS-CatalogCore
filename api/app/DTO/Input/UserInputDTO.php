<?php

namespace App\DTO\Input;

use Illuminate\Http\Request;

class UserInputDTO
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public static function fromRequest(Request $request){
        return new UserInputDTO($request->get('email'));
    }
}
