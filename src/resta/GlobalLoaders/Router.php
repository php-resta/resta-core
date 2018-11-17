<?php

namespace Resta\GlobalLoaders;

use Resta\App;
use Resta\FileProcess;
use Resta\StaticPathModel;
use Resta\ApplicationProvider;
use Symfony\Component\Yaml\Yaml;
use Resta\Traits\NamespaceForRoute;

class Router extends ApplicationProvider
{
    //get namespace for route and instance
    use NamespaceForRoute;

    /**
     * @param bool $unset
     * @return void
     */
    public function route($unset=false)
    {
        if(file_exists($serviceDummy=StaticPathModel::getServiceDummy())){
            $serviceDummy=Yaml::parse(file_get_contents($serviceDummy));
        }

        //utils make bind via dependency injection named as service container
        $this->register('logger',                   app()->namespace()->logger());
        $this->register('serviceConf',              (new FileProcess())->callFile(StaticPathModel::getServiceConf()));
        $this->register('serviceDummy',      (isset($serviceDummy)) ? $serviceDummy : []);
        $this->register('instanceController',       $this->makeBind($this->getControllerNamespace()));
        $this->register('serviceConf',              $this->singleton()->serviceConf);
    }

    /**
     * @param $method
     * @return void
     */
    public function substractMethodNameFromRouteParameters($method)
    {
        $this->register('method',$method);
        $this->register('url','method',$this->resolveMethod($method));
        $this->register('url','method',$this->singleton()->url['method']);
        $this->terminate('routeParameters');
        $this->register('routeParameters', $this->routeParametersAssign($this->resolveMethod($method)));

    }

    /**
     * @param $methodName
     * @return void|mixed
     */
    public function setMethodNameViaDefine($methodName)
    {
        define('methodName',strtolower($methodName));
    }


}