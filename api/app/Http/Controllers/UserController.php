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

    /*
     * @Override
     */
    protected function mapEntity($entity) {
        $user_scope_names = DB::table('user_allowed_scope')
            ->join('scope', 'scope.id', '=', 'user_allowed_scope.scope_id')
            ->where('user_id', $entity->id)
            ->pluck('scope.name')
            ->toArray();

        return [
            ...((array)$entity),
            'allowed_scopes' => $user_scope_names
        ];
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

        $newUser = ['email' => $input['email']];

        $data = $this->repository->createOne($newUser);

        if (isset($input['allowed_scopes'])) {
            $allowed_scopes_ids = DB::table('scope')
                ->whereIn('name', $input['allowed_scopes'])
                ->pluck('id')
                ->toArray();

            DB::table('user_allowed_scope')
                ->insert(
                    array_map(
                        fn($id) => ['user_id' => $data->id, 'scope_id' => $id ], 
                        $allowed_scopes_ids
                    )
                );
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

        if (isset($input['email'])) {
            DB::table('user')
                ->where('id', '=', $input['id'])
                ->update(['email' => $input['email']]);
        }

        if (isset($input['allowed_scopes'])) {
            DB::table('user_allowed_scope')
                ->where('user_id', $input['id'])->delete();

            $allowed_scopes_ids = DB::table('scope')
                ->whereIn('name', $input['allowed_scopes'])
                ->pluck('id')
                ->toArray();

            DB::table('user_allowed_scope')
                ->insert(
                    array_map(
                        fn($id) => ['user_id' => $input['id'], 'scope_id' => $id], 
                        $allowed_scopes_ids
                    )
                );
        }

        $data = DB::table('user')
            ->where('id', $input['id'])
            ->first();

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    protected function validatedIfNecessary(string $action, Request $request) {
        $request['id'] = $request->id;

        return parent::validatedIfNecessary($action, $request);
    }
}
