<?php

namespace App\Exceptions;
use Exception;
class DuplicatedKeyException extends Exception
{
    protected $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
        parent::__construct($message, 500);
    }
}
