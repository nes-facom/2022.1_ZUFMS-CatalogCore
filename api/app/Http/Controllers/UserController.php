<?php

namespace App\Http\Controllers;

use App\DTO\DTOValidationException;
use App\DTO\Input\CreateUserRequestDTO;
use App\DTO\Input\UpdateUserRequestDTO;
use App\Repository\GenericRepository;
use Illuminate\Http\Request;
use App\Helpers\ArrayHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends CRUDController
{
    public function __construct()
    {
        parent::__construct(
            repository: new GenericRepository('user'),
            dtos: [
                'createOne' => CreateUserRequestDTO::class,
                'updateOne' => UpdateUserRequestDTO::class
            ]
        );
    }

    protected function mapEntity($user) {
        return ArrayHelper::array_omit((array)$user, ['password']);
    }

    /*
     * @Override
     */
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

        foreach ($input['scope_id'] as $scope_id){
            DB::table('user_allowed_scope')
                ->insert([
                    'user_id' => $data->id,
                    'scope_id' => $scope_id
                ]);
        }

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    /*
     * @Override
     */
    public function updateOne(Request $request)
    {
        $input;

        try {
            $input = $this->validatedIfNecessary(__FUNCTION__, $request);
        } catch (DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        unset($input['access_token']);

        if ($input['email'] != null) {
            DB::table('user')
                ->where('id', '=', $input['id'])
                ->update(['email' => $input['email']]);
        }

        if ($input['scope_id'] != null) {
            DB::table('user_allowed_scope')
                ->where('user_id', $input['id'])->delete();

            foreach ($input['scope_id'] as $scope_id){
                DB::table('user_allowed_scope')
                    ->insert([
                        'user_id' => $input['id'],
                        'scope_id' => $scope_id
                    ]);
            }
        }
        return response()->json([
            'message' => 'Update successful'
        ], 200);
    }

    private function validatedIfNecessary(string $action, Request $request) {
        $request['id'] = $request->id;
        if (isset($this->dtos[$action])) {
            return $this->dtos[$action]::fromRequest($request)
                ->toArray();
        }

        return $request->all();
    }
}
