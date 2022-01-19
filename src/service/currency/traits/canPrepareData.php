<?php

namespace App\Service\Currency\Traits;

use App\Lib\Request\Client;
use App\Service\Currency\CurrencyListHelper;
use App\Service\Currency\CurrencyService;

trait canPrepareData
{

    private function getCurrencyRateFromApi()
    {
        $response = Client::get(CurrencyService::DEFAULT_CURRENCY_ENDPOINT);
        //check if response is valid
        if (!$response) {
            throw new \Exception('Invalid Response From the Currency Rate Vendor', 500);
        }
        $response = json_decode($response, true);
        //check if response is valid and array
        if (!is_array($response)) {
            throw new \Exception('Invalid Response Type. Did not recieve Expected response From the Currency Rate Vendor', 500);
        }
        return $response;
    }

    private function prepareConversionData($response)
    {
        $currencies = $response['data'];
        $data = [];
        $data['baseCurrency'] = $response['query']['base'] ?? 'USD';
        $data['timestamp'] = time();
        $currencyHelper = new CurrencyListHelper();
        foreach ($currencies as  $currencyCode => $conversionRate) {
            $d = $currencyHelper->getDetailsByCurrencyCode($currencyCode);

            $loc = $this->getLocfromData($d);
            $d = current($d);
            if (!is_array($d)) {
                continue;
            }
            $temp = [];
            $temp['currencyCode'] = $currencyCode;

            $temp['currencyName'] = $d['CcyNm'];
            $temp['conversionRate'] = $conversionRate;
            $temp['country'] = $d['CtryNm'];
            $temp['loc'] = implode(',', $loc);
            $temp['countryCode'] = $currencyCode;
            $data['currencies'][$currencyCode] = $temp;
        }
        return $data;
    }


    private function getLocfromData(array $data)
    {
        $loc = [];
        foreach ($data as $currency) {
            $loc[] = $currency['CtryNm'];
        }
        return $loc;
    }
}
