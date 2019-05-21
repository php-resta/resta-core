<?php

namespace Resta\Support;

use Spatie\ArrayToXml\ArrayToXml;

class SimpleXmlManager
{
    /**
     * @var object
     */
    protected $xml;

    /**
     * SimpleXmlManager constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->xml = \simplexml_load_file($path);
    }

    /**
     * get xml object
     *
     * @return object|\SimpleXMLElement
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Convert XML to an Array
     *
     * @return array
     */
    public function toArray()
    {
        $xml = $this->getXml();

        return json_decode(json_encode((array) $xml), true);
    }

    /**
     * Convert Array to an xml
     *
     * @param array $data
     * @return string
     */
    public function toXml($data=array())
    {
        return ArrayToXml::convert($data);
    }
}