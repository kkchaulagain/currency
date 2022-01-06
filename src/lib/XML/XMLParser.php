<?php

namespace App\Lib\XML;

class XMLParser
{
    public $data;
    public function __construct(array $json)
    {
        $this->data = $json;
    }

    public function parse()
    {
        $xml = new \SimpleXMLElement('<root/>');
        $this->arrayToXml($this->data, $xml);
        return $xml;
    }

    //function to convert array to xml
    public function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    $this->arrayToXml($value, $subnode);
                } else {
                    $subnode = $xml->addChild("item$key");
                    $this->arrayToXml($value, $subnode);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
        $xml->asXML();
    }

    public function save($file)
    {
        $xml = $this->parse();
        $xml->asXML($file);
    }


    public static function parseAndSave($data, $file)
    {
        $parser = new XMLParser($data);
        $parser->save($file);
    }

    public static function convertXmlToArray($xml)
    {
        $array = json_decode(json_encode((array) simplexml_load_string($xml)), true);
        return $array;
    }
}
