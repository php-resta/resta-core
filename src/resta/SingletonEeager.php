<?php

namespace Resta;

use Resta\Traits\NamespaceForRoute;
use Symfony\Component\Yaml\Yaml;

class SingletonEeager extends ApplicationProvider  {

    //get namespace for route
    use NamespaceForRoute;

    /**
     * @method route
     * @param $unset false
     * return mixed
     */
    public function route($unset=false){

        //We make include the service conf file in the kernel object
        //
        $serviceConf=(new FileProcess())->callFile(StaticPathModel::getServiceConf());

        //
        if(file_exists($serviceDummy=StaticPathModel::getServiceDummy())){
            $serviceDump=Yaml::parse(file_get_contents($serviceDummy));
        }

        //utils make bind via dependency injection named as service container
        $this->app->singleton()->serviceConf=$serviceConf;
        $this->app->singleton()->serviceDummy=(isset($serviceDump)) ? $serviceDump : [];
        $this->app->singleton()->instanceController=$this->makeBind($this->getControllerNamespace());
    }

}