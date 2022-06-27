<?php

namespace App\Repository;


use App\DTO\Input\OccurrenceInputDTO;
use Illuminate\Support\Facades\DB;

class CollectionRepository extends GenericRepository
{

    public function generateSql( $array)  {
        $sql = "insert into \"biological_occurrence_view\" (";


        $values = " values (";
        $keys = array_keys($array);
        $size = count($array);

        for ($i = 0; $i < $size; $i++) {

            $key = $keys[$i];
            $jsonOccurrence = $array[$key];

            $values = "{$values}'{$jsonOccurrence}'";
            $sql = "{$sql}\"{$key}\"";
            if($i < $size-1){
                $values = "{$values},";
                $sql = "{$sql},";
            }else{
                $values = "{$values})";
                $sql = "{$sql}) values(";
            }

        }

        for ($i = 0; $i < $size; $i++) {
            $sql = "{$sql}?";
            if($i < $size-1){
                $sql = "{$sql},";
            }else{
                $sql = "{$sql})";
            }
        }
        dd($sql);
        DB::insert($sql, $array);


    }


}


