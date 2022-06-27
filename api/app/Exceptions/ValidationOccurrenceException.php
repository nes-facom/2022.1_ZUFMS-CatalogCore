<?php

namespace App\Exceptions;
use Exception;
class ValidationOccurrenceException extends Exception
{
    public array $errorsArray;

    /**
     * @param $errorsArray
     */
    public function __construct($errorsArray)
    {
        $this->errorsArray = $errorsArray;
        parent::__construct("Validation error", 500);
    }
}
