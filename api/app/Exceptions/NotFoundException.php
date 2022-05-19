<?php

namespace App\Exceptions;
use Exception;
class NotFoundException extends Exception
{
    protected $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
//        parent::__construct($message, 404);
    }

    public static function render(){
        return response()->json([
            'status' => 404,
            'message' => 'not fooound'
        ]);
    }


}
