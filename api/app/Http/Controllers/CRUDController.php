<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\GenericRepository;
use App\DTO\DTOValidationException;
use App\Helpers\ArrayHelper;

class CRUDController extends Controller
{
    public function __construct(
        public GenericRepository $repository,
        public array $dtos = [],
        public array $entity_pk = ['id'],
    )
    { }

    protected function mapEntity($entity) {
        return $entity;
    }

    protected function validatedIfNecessary(string $action, Request $request) {
        if (isset($this->dtos[$action])) {
            return $this->dtos[$action]::fromRequest($request)
                    ->toArray();
        }

        return $request->all();
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

        $data = $this->repository->createOne($input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function updateOne(Request $request) {
        $id = $request->route('id');
        $input;
        
        try {
            $input = $this->validatedIfNecessary(__FUNCTION__, $request);
        } catch (DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        unset($input['access_token']);

        $data = $this->repository->updateOne($id, $input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function deleteOne(Request $request) {
        $id = $request->route('id');
        $input;
        
        try {
            $input = $this->validatedIfNecessary(__FUNCTION__, $request);
        } catch (DTOValidationException $e) {
            return response()->json([
                'errors' => $e->errors
            ], 400);
        }

        $data = $this->repository->deleteOne($id, $input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function getOne(Request $request) {
        $id = $request->route('id');

        $data = $this->repository->getOne($id);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function getAll(Request $request) {
        $data = $this->repository->getAll();

        $mapped_data = array_map([$this, 'mapEntity'], $data);

        return response()->json($mapped_data);
    }

    public function count(Request $request) {
        $data = $this->repository->count();

        return response()->json($data);
    }
}
