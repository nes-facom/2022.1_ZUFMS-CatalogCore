<?php

namespace App\Http\Controllers;

use App\DTO\DTOValidationException;
use App\DTO\Input\CreateUserRequestDTO;
use App\Repository\GenericRepository;
use Illuminate\Http\Request;
use App\Helpers\ArrayHelper;
use Illuminate\Support\Facades\DB;

class UserController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(
            repository: new GenericRepository('user'),
            dtos: [
                'createOne' => CreateUserRequestDTO::class
            ]
        );
    }

    protected function mapEntity($user) {
        return ArrayHelper::array_omit((array)$user, ['password']);
    }

    public function createOne(Request $request) {
        $input;

        try {
            $input = $this->validatedIfNecessary(__FUNCTION__, $request);
        } catch (DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        unset($input['access_token']);

        $newUser = ['email' => $input['email']];
        $data = $this->repository->createOne($newUser);

        DB::table('user_allowed_scope')
            ->insert([
                'user_id' => $data->id,
                'scope_id' => $input['scope']
            ]);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    private function validatedIfNecessary(string $action, Request $request) {
        if (isset($this->dtos[$action])) {
            return $this->dtos[$action]::fromRequest($request)
                ->toArray();
        }

        return $request->all();
    }
}
