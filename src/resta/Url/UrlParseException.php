<?php

namespace Resta\Url;

use DomainException;
use Resta\Foundation\PathManager\StaticPathModel;

class UrlParseException
{
    /**
     * @param array $data
     */
    public function exception($data=array())
    {
        if(!isset($data['project']) and !isset($data['version'])){
            throw new DomainException('No Project or Version');
        }

        //get app path for checking
        $appPath = StaticPathModel::appPath().'/'.$data['project'];

        //If there is no project on the url
        //we throw an exception
        if($data['project']===null OR !file_exists($appPath)){
            throw new DomainException('No Project');
        }

        if(!in_array($data['version'],UrlVersionIdentifier::supportedVersions())){
            throw new DomainException('Version Number is not supported');
        }

        //If there is no endpoint on the url
        //we throw an exception
        if($data['endpoint']===null){
            throw new DomainException('No Endpoint');
        }
    }
}