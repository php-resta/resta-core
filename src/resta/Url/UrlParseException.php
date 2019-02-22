<?php

namespace Resta\Url;

use Resta\Foundation\StaticPathModel;

class UrlParseException
{
    /**
     * @param null $data
     */
    public function exception($data=null)
    {
        //get app path for checking
        $appPath=StaticPathModel::appPath().'/'.$data['project'];

        //If there is no project on the url
        //we throw an exception
        if($data['project']===null OR !file_exists($appPath)){
            throw new \DomainException('No Project');
        }

        //If there is no endpoint on the url
        //we throw an exception
        if($data['endpoint']===null){
            throw new \DomainException('No Endpoint');
        }
    }
}