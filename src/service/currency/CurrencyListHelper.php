<?php

namespace App\Service\Currency;

use App\Lib\Request\Client;

class CurrencyListHelper
{
    public $response;
    protected $currencyLists;
    public function __construct()
    {
        $this->setCurrencyLists();
    }

    public function setCurrencyLists()
    {
        $response = Client::get('https://www.six-group.com/dam/download/financial-information/data-center/iso-currrency/lists/list_one.xml');
        $response = simplexml_load_string($response);
        //convert xml to json
        $json = json_encode($response);
        //convert json to php array
        $response = json_decode($json, true);
        $this->currencyLists = $response;
    }


    public function getDetailsByCurrencyCode($currencyCode)
    {
        return $this->findByValue($currencyCode);
    }



    //function to find key of array by value
    public function findByValue($value)
    {
        $array = $this->currencyLists['CcyTbl']['CcyNtry'];
     
        foreach ($array as $val) {
            // var_dump($val);
            // die;
            if (strtolower($val['Ccy']) == strtolower($value)) {

                return $val;
            }
        }
        return null;
    }
}
