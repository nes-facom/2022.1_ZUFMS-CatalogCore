<?php

namespace App\DTO\Input;

use Opis\JsonSchema\{Errors\ErrorFormatter, ValidationResult, Validator,};

class ListOccurrenceInputDTO
{
    /**
     * list of occurrences.
     * @var array<int, OccurrenceInputDTO>
     */
    public array $occurrences;

    /**
     * @param $occurrences
     */
    public function __construct($occurrences)
    {
        $this->occurrences = $occurrences;
    }

    public static function constructEmpty(): ListOccurrenceInputDTO
    {
        return new ListOccurrenceInputDTO([]);
    }
}
