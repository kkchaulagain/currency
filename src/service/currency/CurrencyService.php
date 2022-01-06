<?php

namespace App\Service\Currency;

use App\Lib\Convertor\Convertor;


class CurrencyService
{
    public const DEFAULT_BASE_CURRENCY = 'USD';
    public const CURRENCY_FILE_NAME = __DIR__ . '/../../../currency.xml';

    public const DEFAULT_CURRENCY_ENDPOINT = 'https://freecurrencyapi.net/api/v2/latest?apikey=205cc040-594c-11ec-95cf-a791ab6e6457';

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
        throw new \Exception("Currency not supported");
    }


    public function convert($amount, $from, $to)
    {

        $config = [
            'baseCurrency' => $from,
            'targetCurrency' => self::DEFAULT_BASE_CURRENCY,
            'amount' => $amount,
            'conversionRate' => $this->getConversionRate($from)
        ];
        $baseValue = (new Convertor($config))->convertToBase();

        $config = [
            'baseCurrency' => self::DEFAULT_BASE_CURRENCY,
            'targetCurrency' => $to,
            'amount' => $baseValue,
            'conversionRate' => $this->getConversionRate($to)
        ];
        $val =  (new Convertor($config))->convertFromBase();
        return $this->formatOutput($from, $to, $amount, $val);
    }


    public function formatOutput($from, $to, $amount, $val)
    {
        return [
            [
                'at' => date('Y-m-d H:i:s'),
                'rate' => $val / $amount,
                'from' => [
                    'code' => $from,
                    'amnt' => $amount

                ],
                'to' => [
                    'code' => $to,
                    'amnt' => $val
                ]
            ]
        ];
    }
}
