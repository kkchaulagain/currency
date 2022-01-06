<?php

namespace App\Lib\Convertor;

class Convertor
{


    public $baseCurrency, $targetCurrency, $amount;
    public function __construct(array $config)
    {
        $this->baseCurrency = $config['baseCurrency'];
        $this->conversionRate = $config['conversionRate'];
        $this->amount = $config['amount'];
    }

    public function convertToBase()
    {
        return $this->amount / $this->conversionRate;
    }

    public function convertFromBase()
    {
        return $this->amount * $this->conversionRate;
    }
}
