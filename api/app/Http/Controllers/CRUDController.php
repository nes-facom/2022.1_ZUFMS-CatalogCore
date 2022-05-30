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

    public function mapEntity($entity) {
        return $entity;
    }

    private function validatedIfNecessary(string $action, Request $request) {
        if (isset($this->dtos[$action])) {
            try {
                return $this->dtos[$action]::fromRequest($request)
                    ->toArray();
            } catch (DTOValidationException $e) {
                return response()->json([
                    'errors' => $e['errors']
                ], 400);
            }
        }

        return $request->all();
    }

    public function createOne(Request $request) {
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

        $data = $this->repository->createOne($input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function updateOne(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

        $data = $this->repository->updateOne($input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function deleteOne(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

        $data = $this->repository->deleteOne(
            ArrayHelper::array_pick($input, $this->entity_pk)
        );

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function getOne(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

        $data = $this->repository->getOne(
            ArrayHelper::array_pick($input, $this->entity_pk)
        );

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function getAll(Request $request) {
        $data = $this->repository->getAll();

        $mapped_data = array_map([$this, 'mapEntity'], $data);

        return response()->json($mapped_data);
    }
}
