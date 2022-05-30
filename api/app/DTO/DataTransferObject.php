<?php

namespace App\DTO;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DTOValidationException extends Exception {
    public function __construct(
        public array $errors
    ) {
        return parent::__construct("", 400);
    }
}

abstract class DataTransferObject
{
    abstract protected static function getValidationRules($rawInput);
    abstract public static function fromArray(array $array);

    protected static function validate($input) {
        $validator = Validator::make($input, static::getValidationRules($input));
    
        if ($validator->fails()) {
            $errors = array_map(fn (string $description) => [
                'code' => 2,
                'title' => 'Dado inválido',
                'description' => $description
            ],  $validator->errors()->all());

            throw new DTOValidationException($errors);
        }

        return $validator->safe()->all();
    }

    public static function fromRequest(Request $request){
        $result = self::validate($request->all());

        return static::fromArray($result);
    }

    public function toArray() {
        $data = $this;

        if (is_array($data) || is_object($data)) {
            $result = [];

            foreach ($data as $key => $value) {
                $result[$key] = (is_array($value) || is_object($value)) ? object_to_array($value) : $value;
            }
            
            return $result;
        }
        
        return $data;
    }
}