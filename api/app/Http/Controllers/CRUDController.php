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
        $id = $request->route('id');
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

        unset($input['access_token']);

        $data = $this->repository->updateOne($id, $input);

        $mapped_data = $this->mapEntity($data);

        return response()->json($mapped_data);
    }

    public function deleteOne(Request $request) {
        $id = $request->route('id');
        $input = $this->validatedIfNecessary(__FUNCTION__, $request);

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
}
