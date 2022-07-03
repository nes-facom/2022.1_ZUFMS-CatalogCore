<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class GenericRepository
{
    public function __construct(
       public string $table_name,
       public string $entity_pk = "id"
    )
    { }

    private function db() {
        return DB::table($this->table_name);
    }

    public function getOne($id) {
        return $this->db()
            ->where($this->entity_pk, $id)
            ->first();
    }

    public function getAll() {
        return $this->db()->get()->toArray();
    }

    public function createOne($data) {
        $id = $this->db()->insertGetId($data);

        return $this->db()
            ->where($this->entity_pk, $id)
            ->first();
    }

    public function deleteOne($id) {
        $data = $this->db()
            ->where($this->entity_pk, $id)
            ->first();

        $this->db()
            ->where($this->entity_pk, $id)
            ->delete();

        return $data;
    }

    public function updateOne($id, $data) {
        $this->db()
            ->where($this->entity_pk, $id)
            ->update($data);

        return $this->db()
            ->where($this->entity_pk, $id)
            ->first();
    }

    public function count() {
        return $this->db()->count($this->entity_pk);
    }
}
