<?php

namespace Resta\GlobalLoaders;

use Resta\FileProcess;
use Resta\Routing\Route;
use Resta\StaticPathModel;
use Resta\ApplicationProvider;
use Resta\Traits\NamespaceForRoute;

class Router extends ApplicationProvider
{
    //get namespace for route and instance
    use NamespaceForRoute;

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function route()
    {
        $namespace = $this->getControllerNamespace();

        //utils make bind via dependency injection named as service container
        $this->register('serviceConf',              (new FileProcess())->callFile(StaticPathModel::getServiceConf()));
        $this->register('instanceController',       $this->makeBind($namespace));
        $this->register('serviceConf',              $this->singleton()->serviceConf);
    }

    /**
     * @param $method
     * @return void
     */
    public function substractMethodNameFromRouteParameters($method)
    {
        $fromRoutes = Route::getRouteResolve();

        if(count($fromRoutes)){
            $method = $fromRoutes['method'];
        }

        $this->register('method',$method);
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