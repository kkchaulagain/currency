<?php

use App\Lib\Response\Response;
use App\Service\Currency\CurrencyHelper;

require './../vendor/autoload.php';

try {
    define('PARAMS', array('curr', 'action'));
    //get method from url
    $method = $_SERVER['REQUEST_METHOD'];
    //get paarams of PUT and DELETE
    if ($method == 'PUT' || $method == 'DELETE' || $method == 'POST') {
        $params = json_decode(file_get_contents('php://input'), true);
    } else {
        $method =  strtoupper($_GET['action']);

    }

    // var_dump($method);
    // die;
    # ensure PARAM values match the keys in $GET
    // if (count(array_intersect(PARAMS, array_keys($params))) < 2) {
    //     throw new Exception("Parameters Missing. The required Paramaters are curr and action. All these paraneters are required", 1000);
    // }
    # ensure no extra params
    // if (count($params) > 3) {
    //     throw new Exception("Too many Parameters. The allowed Paramaters are curr and action", 1000);
    // }
    $format =  'xml';
    $currency = $params['curr'];
    $action = $method;


    $currencyHelper = new CurrencyHelper($action, $currency);
    $res = $currencyHelper->updateCurrencyData();
    $response = new Response(200, $res, $format);
    $response->send();
} catch (\Exception $e) {

    $res = [
        'conv' => [
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]
        ]

    ];
    $format =  'xml';
    $response = new Response(500, $res, $format);
    $response->send();
}
