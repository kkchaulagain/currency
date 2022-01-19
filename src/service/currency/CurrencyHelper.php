<?php

namespace App\Service\Currency;

use App\Lib\XML\XMLParser;
use App\Service\Currency\Traits\canPrepareData;

class CurrencyHelper
{
    use canPrepareData;

    public $response, $method, $file, $currency;

    private $update;

    public function __construct($method, $currency)
    {
        $this->file = CurrencyService::CURRENCY_FILE_NAME;
        $this->method = $method;
        $this->currency = $currency;

        if ($this->currency == CurrencyService::DEFAULT_BASE_CURRENCY) {
            throw new \Exception("Cannot Update Base Currency", 2400);
        }
        $this->pullDataForCurrency();
    }



    public function pullDataForCurrency()
    {
        $response = $this->getCurrencyRateFromApi();
        $this->update = $this->getCurrencyData($this->prepareConversionData($response));
    }


    public function updateCurrencyData()
    {
        date_default_timezone_set('Europe/London');
        $response = [
            'action' => [
                'Attribute_type' => $this->method,
                'at' => date('d F Y H:i'),
                'curr' => [
                    'code' => $this->currency,

                ]
            ]
        ];

        $olData = file_get_contents($this->file);
        //convert xml to array
        $olData = XMLParser::convertXmlToArray($olData);
        // $olData = json_decode($olData);
        if (strtoupper($this->method) == 'DELETE') {

            unset($olData['currencies'][$this->currency]);
            XMLParser::parseAndSave($olData, $this->file);
            return $response;
        } else if (strtoupper($this->method) == "POST") {
            $olData['currencies'][$this->currency] = $this->update;
            XMLParser::parseAndSave($olData, $this->file);
            $response['action']['rate'] = $this->update['conversionRate'];
            $response['action']['curr'] = [
                'code' => $this->currency,
                'name' => $this->update['currencyName'],
                'loc'=> $olData['currencies'][$this->currency]['loc'],
            ];
            return $response;
        } else if (strtoupper($this->method) == 'PUT') {

            if ($oldValue = $olData['currencies'][$this->currency]['conversionRate']) {
                $olData['currencies'][$this->currency] = $this->update;
                $olData['currencies'][$this->currency] = $this->update;
                XMLParser::parseAndSave($olData, $this->file);

                $response['action']['rate'] = $this->update['conversionRate'];
                $response['action']['old_rate'] = $oldValue;
                $response['action']['curr'] = [
                    'code' => $this->currency,
                    'name' => $this->update['currencyName'],
                    'loc'=> $olData['currencies'][$this->currency]['loc']
                ];
                return $response;
            } else {
                throw new \Exception("Currency not found . Why not try Post Method Instead?", 2300);
            }
        }
    }


    private function getCurrencyData($response)
    {
        $data = $response['currencies'];
        if (isset($data[$this->currency])) {
            return  $data[$this->currency];
        }
        throw new \Exception("currency wrong format or missing",2100);
    }
}
