<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class GenericRepository
{
    public function __construct(
        public string $table_name
    )
    { }

    private function db() {
        return DB::table($this->table_name);
    }

    public function getOne($id) {
        return $this->db()
            ->find($id);
    }

    public function getAll() {
        return $this->db()->get()->toArray();
    }

    public function createOne($data) {
        $id = $this->db()->insertGetId($data);

        return $this->db()->find($id);
    }

    public function deleteOne($id) {
        $data = $this->db()
            ->find($id);
        
        $this->db()
            ->where('id', $id)
            ->delete();

        return $data;
    }

    public function updateOne($id, $data) {
        $this->db()
            ->where('id', $id)
            ->update($data);

        return $this->db()
            ->find($id); 
    }
}
