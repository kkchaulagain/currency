<?php

namespace App\Lib\Request;

class Client
{
    public $url, $method, $body, $headers;

    public function __construct($config)
    {
        $this->url = $config['url'];
        $this->method = $config['method'];
        $this->body = $config['body'] ?? null;
        $this->headers = $config['headers'] ?? [];
    }

    public function request()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function get($url)
    {
        $client =  new Client([
            'url' => $url,
            'method' => 'GET'
        ]);
        return $client->request();
    }

    public static function post($url, $body)
    {
        $client =  new Client([
            'url' => $url,
            'method' => 'POST',
            'body' => $body
        ]);
        return $client->request();
    }
    
    public static function put($url, $body)
    {
        $client =  new Client([
            'url' => $url,
            'method' => 'PUT',
            'body' => $body
        ]);
        return $client->request();
    }
}
