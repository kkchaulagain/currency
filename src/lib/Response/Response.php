<?php

namespace App\Lib\Response;

class Response
{
    public $statusCode;
    public $body;
    public $headers;

    public function __construct($statusCode, $body,  $format = 'xml', $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
        $this->format = $format;
        if ($format == 'xml') {
            $this->headers[] = 'Content-Type: application/xml';
        } elseif ($format == 'json') {
            $this->headers[] = 'Content-Type: application/json';
        }
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $header) {
            header($header);
        }
        echo $this->getBody();
    }


    private function getBody()
    {
        if ($this->format == 'xml') {
            // var_dump($this->body);
            // die;
            $response = new XMLResponse($this->body);
        } elseif ($this->format == 'json') {
            $response = new JSONResponse($this->body);
        }
        return $response->send();
        exit();
    }
}
