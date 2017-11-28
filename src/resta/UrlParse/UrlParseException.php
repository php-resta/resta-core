<?php

namespace Resta\UrlParse;

use Resta\StaticPathModel;

/**
 * Class UrlParseException
 * @package Resta\UrlParse
 */
class UrlParseException {

    /**
     * @method exception
     * @param null $data
     * @return mixed
     */
    public function exception($data=null){

        //get app path for checking
        $appPath=StaticPathModel::appPath().'/'.$data['project'];

        //If there is no project on the url
        //we throw an exception
        if($data['project']===null OR !file_exists($appPath)){
            throw new \DomainException('No Project');
        }

        //If there is no namespace on the url
        //we throw an exception
        if($data['namespace']===null){
            throw new \DomainException('No Namespace');
        }

        //If there is no endpoint on the url
        //we throw an exception
        if($data['endpoint']===null){
            throw new \DomainException('No Endpoint');
        }
    }

}