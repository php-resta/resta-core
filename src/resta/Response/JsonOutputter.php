<?php

namespace Resta\Response;

use Resta\Traits\ResponseOutput;
use Symfony\Component\HttpFoundation\Response;

class JsonOutputter {

    //get response output
    use ResponseOutput;

    /**
     * @method handle
     * @return string
     */
    public function handle(){

        //header set and symfony response call
        header('Content-type:application/json;charset=utf-8');
        $response = new Response();

        //json data set and get content from symfony response
        $response->setContent(json_encode($this->getOutPutter()));
        return $response->getContent();

    }


}