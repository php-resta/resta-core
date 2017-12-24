<?php

namespace Resta\GlobalLoaders;

use Resta\App;
use Resta\ApplicationProvider;
use Resta\FileProcess;
use Resta\StaticPathModel;
use Resta\Traits\NamespaceForRoute;
use Symfony\Component\Yaml\Yaml;

class Router extends ApplicationProvider  {

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
        $this->singleton()->instanceController      = $this->makeBind($this->getControllerNamespace());
    }

    /**
     * @param $method
     * @return mixed
     */
    public function substractMethodNameFromRouteParameters($method){

        $this->singleton()->routeParameters=$this->routeParametersAssign($this->resolveMethod($method));
        define('appInstance',(base64_encode(serialize($this))));
        class_alias(App::class,'application');
    }


}