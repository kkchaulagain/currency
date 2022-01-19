<?php

namespace App\Service\Currency;

use App\Lib\Convertor\Convertor;


class CurrencyService
{
    public const DEFAULT_BASE_CURRENCY = 'GBP';
    public const CURRENCY_FILE_NAME = __DIR__ . '/../../../currency.xml';

    public const DEFAULT_CURRENCY_ENDPOINT = 'https://freecurrencyapi.net/api/v2/latest?apikey=205cc040-594c-11ec-95cf-a791ab6e6457&base_currency=' . self::DEFAULT_BASE_CURRENCY;

    protected $conversionHelper;
    public function __construct()
    {
        $this->loadCurrencyFile();
    }


    private function loadCurrencyFile()
    {
        $file = self::CURRENCY_FILE_NAME;
        $newFile = false;
        if (!file_exists($file)) {
            $newFile  = true;
        }
        $this->conversionHelper = new ConversionHelper($file, $newFile);
    }

    public function getConversionRate($currency)
    {
        if ($currency === self::DEFAULT_BASE_CURRENCY) {
            return 1;
        }
        $data = $this->conversionHelper->response['currencies'];
        if (isset($data[$currency])) {
            return  $data[$currency]['conversionRate'];
        }
        throw new \Exception("Currency not supported", 1200);
    }

    public function is_decimal($amount) {
        //check if the amount has decimal places
          if (strpos($amount, '.') !== false) {
              return true;
          }
          return false;
      }
      

    public function convert($amount, $from, $to)
    {
        if ($amount > 0) {

            // round to 2 decimal places


            $config = [
                'baseCurrency' => $from,
                'targetCurrency' => self::DEFAULT_BASE_CURRENCY,
                'amount' => round($amount,2),
                'conversionRate' => $this->getConversionRate($from)
            ];
            $baseValue = (new Convertor($config))->convertToBase();

            $config = [
                'baseCurrency' => self::DEFAULT_BASE_CURRENCY,
                'targetCurrency' => $to,
                'amount' => round($baseValue,2),
                'conversionRate' => $this->getConversionRate($to)
            ];
            $val =  (new Convertor($config))->convertFromBase();
        } else {
            $amount = 0;
            $val = 0;
        }
        return $this->formatOutput($from, $to, $amount, $val);
    }


    public function formatOutput($from, $to, $amount, $val)
    {
        $rate = $amount > 0 ? $val / $amount : 0;
        // $amount = $this->is_decimal($amount) ? $amount : number_format($amount, 2);
        return [
            //19 Jan 2020
            'at' => date('d F Y'),
            'rate' => round($rate,2),
            'from' => [
                'code' => $from,
                'amnt' => number_format($amount,2),
                'loc' => $this->conversionHelper->response['currencies'][$from]['loc'] ?? ''

            ],
            'to' => [
                'code' => $to,
                'amnt' => number_format($val,2),
                'loc' => $this->conversionHelper->response['currencies'][$to]['loc'] ?? ''
            ]

        ];
    }
}
