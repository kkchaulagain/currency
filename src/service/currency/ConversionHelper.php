<?php

namespace App\Service\Currency;

use App\Lib\XML\XMLParser;
use App\Service\Currency\Traits\canPrepareData;

class ConversionHelper
{
    use canPrepareData;

    public $response;

    public function __construct($file, $newFile = false)
    {
        $this->file = $file;
        $this->prepare($newFile);
    }

    public function  prepare($newFile)
    {
        if ($newFile) {
            // delete old file
            $this->deleteFile();
        }
        $this->parseXml();
    }

    private  function deleteFile()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    //parse the xml file and return the data
    public function parseXml()
    {
        //check if the file exists
        if (!file_exists($this->file)) {
            //get the data from the api
            $this->getCurrencyRate();
        }
        $xml = simplexml_load_file($this->file);
        $json = json_encode($xml);
        $this->response  = json_decode($json, true);
        if ($this->checkIfXmlIsOld()) {
            $this->deleteFile();
            $this->parseXml();
        }
    }

    //check if the timestamp is more than 2 hours old
    public function checkIfXmlIsOld()
    {
        $data = $this->response;
        $timestamp = (int) $data['timestamp'];
        $date = new \DateTime('@' . (int)$timestamp);
        $now = new \DateTime();
        $diff = $now->diff($date);
        $diff = $diff->h;
        if ($diff > 2) {
            return true;
        }
        return false;
    }


    private function getCurrencyRate()
    {
        $response = $this->getCurrencyRateFromApi();
        $data = $this->prepareConversionData($response);
        XMLParser::parseAndSave($data, CurrencyService::CURRENCY_FILE_NAME);
    }
}
