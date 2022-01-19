<?php



require 'vendor/autoload.php';

use App\Lib\Response\Response;
use App\Service\Currency\CurrencyService;

try {
    //check if the amount has decimal places
    function is_decimal($amount) {
        $decimals = ( (int) $amount != $amount ) ? (strlen($amount) - strpos($amount, '.')) - 1 : 0;
        return $decimals >= 2;
    }
    

    define('PARAMS', array('to', 'from', 'amnt', 'format'));
    if (!isset($_GET['format']) || empty($_GET['format'])) {
        $_GET['format'] = 'xml';
    } else {
        if ($_GET['format'] != 'xml' && $_GET['format'] != 'json') {
            throw new \Exception('Format must be xml or json', 1400);
        }
    }
    # ensure PARAM values match the keys in $GET
    if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
        throw new Exception("Parameters Missing. The required Paramaters are to, from and amnt. All these paraneters are required", 1100);
    }
    # ensure no extra params
    if (count($_GET) > 4) {
        throw new Exception("Too many Parametes. The allowed Paramaters are to, from, amnt and format", 1000);
    }
    //if type string then throw exception
    //check if amount is decimal
    if(!is_decimal($_GET['amnt'])){
        throw new Exception("Amount must be numeric", 1300);
    }
   
    $res = [
        'conv' => (new CurrencyService())->convert($_GET["amnt"], $_GET["from"], $_GET["to"])
    ];
    $response = new Response(200, $res, $_GET['format']);
    $response->send();
    die;
} catch (\Exception $e) {

    $res = [
        'conv' => [
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]
        ]

    ];
    $format =  $_GET['format'] != 'json' ? 'xml' : 'json';
    $response = new Response(500, $res, $format);
    $response->send();
}
