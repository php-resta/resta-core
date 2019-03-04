<?php

namespace Resta\Router;

use Resta\Router\Route;
use Resta\Support\FileProcess;
use Resta\Traits\NamespaceForRoute;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathModel;

class RouterKernelAssigner extends ApplicationProvider
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
        $this->app->register('serviceConf',              (new FileProcess())->callFile(StaticPathModel::getServiceConf()));
        $this->app->register('instanceController',       $this->app->resolve($namespace));
    }

    /**
     * @param $method
     * @return void
     */
    public function substractMethodNameFromRouteParameters($method)
    {
        $fromRoutes = Route::getRouteResolve();
        $method     = (isset($fromRoutes['method'])) ? $fromRoutes['method'] : $method;

        $this->app->register('method',$method);
        $this->app->register('routeParameters', $this->routeParametersAssign($this->resolveMethod($method)));

    }

    /**
     * @param $methodName
     * @return void|mixed
     */
    public function setMethodNameViaDefine($methodName)
    {
        define('methodName',strtolower($methodName));
    }

    /**
     * @param $parameters
     * @return void
     */
    public function routeServiceConfiguration($parameters)
    {
        // we record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method = strtolower(app()->singleton()->url['method']);

        // based on the serviceConf variable,
        // we are doing parameter bindings in the method context in the routeParameters array key.
        $this->app->register('serviceConf','routeParameters',[$method=>$parameters]);

    }


}