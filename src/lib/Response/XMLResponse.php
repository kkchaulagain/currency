<?php

namespace App\Lib\Response;

use App\Lib\Response\Contracts\Response;
use SimpleXMLElement;

class XMLResponse implements Response
{
    public $body;

    public function __construct(array $body)
    {

        $this->body = $body;
    }

    public function send()
    {

        $xml = new SimpleXMLElement('<root/>');
        // $this->arrayToXml($this->body, $xml);
        $this->to_xml($xml, $this->body);
        return $xml->asXML();
    }

    /**
     * Convert an array to XML
     * @param array $array
     * @param SimpleXMLElement $xml
     */
    function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                $key = "e";
            }
            if (is_array($value)) {
                $label = $xml->addChild($key);
                $this->arrayToXml($value, $label);
            } else {
                $xml->addChild($key, $value);
            }
        }
    }

    function to_xml(SimpleXMLElement $object, array $data)
    {
        $attr = "Attribute_";
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->to_xml($new_object, $value);
            } else {
                if (strpos($key, $attr) !== false) {
                    $object->addAttribute(substr($key, strlen($attr)), $value);
                } else {
                    $object->addChild($key, $value);
                }
            }
        }
    }
}
