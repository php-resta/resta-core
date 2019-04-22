<?php

namespace Resta\Response\Outputter;

use Spatie\ArrayToXml\ArrayToXml;

class XmlOutputter {

    /**
     * @param $outputter
     * @return string
     */
    public function handle($outputter){

        header('Content-type:application/xml;charset=utf-8');
        return ArrayToXml::convert($outputter);
    }
}