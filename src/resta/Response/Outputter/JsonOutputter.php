<?php

namespace Resta\Response\Outputter;

use Symfony\Component\HttpFoundation\Response;

class JsonOutputter {

    /**
     * @param $outputter
     * @return string
     */
    public function handle($outputter){

        //header set and symfony response call
        header('Content-type:application/json;charset=utf-8');
        $response = new Response();

        //json data set and get content from symfony response
        $response->setContent(json_encode($outputter));
        return $response->getContent();
    }
}