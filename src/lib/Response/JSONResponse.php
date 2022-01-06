<?php

namespace App\Lib\Response;

use App\Lib\Response\Contracts\Response;

class JSONResponse implements Response
{
    public $body;

    public function __construct(array $body)
    {

        $this->body = $body;
    }

    public function send()
    {
        return json_encode($this->body);
    }
}
