<?php

namespace App\Http\Controllers;

use App\DTO\Input\UserInputDTO;
use App\Repository\GenericRepository;
use Illuminate\Http\Request;
use App\Helpers\ArrayHelper;

class UserController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(
            repository: new GenericRepository('user'),
        );
    }
    
    protected function mapEntity($user) {
        return ArrayHelper::array_omit((array)$user, ['password']);
    }
}
