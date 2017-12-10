<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\FileProcess;
use Resta\StaticPathModel;
use Resta\Traits\NamespaceForRoute;
use Symfony\Component\Yaml\Yaml;

class Route extends ApplicationProvider  {

    //get namespace for route
    use NamespaceForRoute;

    /**
     * @method route
     * @param $unset false
     * return mixed
     */
    public function route($unset=false){
        //
        if(file_exists($serviceDummy=StaticPathModel::getServiceDummy())){
            $serviceDummy=Yaml::parse(file_get_contents($serviceDummy));
        }

        //utils make bind via dependency injection named as service container
        $this->singleton()->serviceConf             = (new FileProcess())->callFile(StaticPathModel::getServiceConf());
        $this->singleton()->serviceDummy            = (isset($serviceDummy)) ? $serviceDummy : [];
        define('appInstance',(base64_encode(serialize($this))));
        $this->singleton()->instanceController      = $this->makeBind($this->getControllerNamespace());
    }

}