<?php

namespace App\Exception;

class SystemException extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    //override the exception handler
    public function exceptionHandler()
    {

        $res = [
            'conv' => [
                'error' => [
                    'code' => $this->getCode(),
                    'message' => $this->getMessage()
                ]
            ]

        ];
        $response = new Response(200, $res, $_GET['format'] ? $_GET['format'] : 'xml');
        $response->send();
    }
}
