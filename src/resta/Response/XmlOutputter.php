<?php

namespace Resta\Response;

use Resta\ApplicationProvider;
use Resta\Traits\ResponseOutput;
use Spatie\ArrayToXml\ArrayToXml;

class XmlOutputter extends ApplicationProvider {

    //get response output
    use ResponseOutput;

    /**
     * @method handle
     * @return string
     */
    public function handle(){

        header('Content-type:application/xml;charset=utf-8');
        return ArrayToXml::convert($this->getOutPutter());
    }


}