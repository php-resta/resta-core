<?php

namespace Resta\GlobalLoaders;

use Resta\App;
use Resta\Utils;
use Resta\FileProcess;
use Resta\StaticPathModel;
use Resta\ApplicationProvider;
use Symfony\Component\Yaml\Yaml;
use Resta\Traits\NamespaceForRoute;

class Router extends ApplicationProvider  {

    //get namespace for route and instance
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
        $this->register('logger',                   StaticPathModel::appServiceLog());
        $this->register('serviceConf',              (new FileProcess())->callFile(StaticPathModel::getServiceConf()));
        $this->register('serviceDummy',      (isset($serviceDummy)) ? $serviceDummy : []);
        $this->register('instanceController',       $this->makeBind($this->getControllerNamespace()));
        $this->register('serviceConf',              $this->singleton()->serviceConf);
    }

    /**
     * @param $method
     * @return mixed
     */
    public function substractMethodNameFromRouteParameters($method){

        $this->register('url',                 'method',$this->resolveMethod($method));
        $this->register('url','method',         $this->singleton()->url['method']);
        $this->terminate('routeParameters');
        $this->register('routeParameters',             $this->routeParametersAssign($this->resolveMethod($method)));

    }


}