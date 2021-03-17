<?php

namespace Resta\Traits;

use Resta\Router\Route;
use Resta\Support\Utils;
use Resta\GlobalLoaders\Router;
use Resta\Router\CheckEndpointForAutoService;
use Resta\Foundation\PathManager\StaticPathModel;

trait NamespaceForRoute
{
    /**
     * @return null|string
     */
    public function project()
    {
        return app;
    }

    /**
     * @return mixed
     */
    public function namespace()
    {
        return core()->urlComponent['namespace'];
    }

    /**
     * @return mixed
     */
    public function endpoint()
    {
        return endpoint;
    }

    /**
     * @return mixed
     */
    public function urlMethod()
    {
        return method;
    }

    /**
     * @return string
     */
    public function method()
    {
        return strtolower(httpMethod()).''.ucfirst(core()->urlComponent['method']);
    }

    /**
     * @param $method
     * @return mixed
     */
    public function resolveMethod($method)
    {
        $method=str_replace(httpMethod(),'',$method);
        $method=str_replace(StaticPathModel::$methodPrefix,'',$method);
        return $method;
    }

    /**
     * @return string
     */
    public function autoService()
    {
        return StaticPathModel::getAutoServiceNamespace().'\\'.$this->endpoint().'\\'.$this->endpoint().''.StaticPathModel::$callClassPrefix;
    }

    /**
     * @return mixed
     */
    public function param()
    {
        return core()->urlComponent['param'];
    }

    /**
     * @return string
     */
    public function getPrefixMethod()
    {
        return $this->method().''.StaticPathModel::$methodPrefix;
    }

    /**
     * @return string
     */
    public function getPrefixIndexMethod()
    {
        //by default we assign a default method value of indexAction
        //this value must be a method value automatically named indexAction
        //if it does not come from the url discovery value
        return httpMethod().'Index'.StaticPathModel::$methodPrefix;
    }

    /**
     * @param $globalInstance
     * @return mixed
     */
    public function checkIfExistsMethod($globalInstance)
    {
        //If controller does not have a method after checking whether the method specified in the controller class exists,
        //then by default we assign a default method value of indexAction.
        //This value must match the class strictly, otherwise the controller can not be called
        if(method_exists($this->app['instanceController'],$this->getPrefixMethod())){

            //method value as normal
            return $this->setViaDefine($this->getPrefixMethod(),$globalInstance);
        }

        //by default we assign a default method value of indexAction
        //this value must be a method value automatically named indexAction
        //if it does not come from the url discovery value
        return $this->setViaDefine($this->getPrefixIndexMethod(),$globalInstance);
    }

    /**
     * @param $method
     * @return array
     */
    public function routeParametersAssign($method)
    {
        //If the path variables give values ​​in the methods do we have a method name,
        //we subtract this value from the route variables.
        return array_values(array_diff(core()->routeParameters,[$method]));
    }

    /**
     * @param $method
     * @param $globalInstance Router
     * @return mixed
     */
    private function setViaDefine($method,$globalInstance)
    {
        // we are extracting httpMethod and prefix from
        // the method variable so that we can extract the salt method name.
        $deleteHttp         = str_replace(httpMethod(),'',$method);
        $methodName         = str_replace(StaticPathModel::$methodPrefix,'',$deleteHttp);

        //set as global method name
        $globalInstance->setMethodNameViaDefine($methodName);

        return $method;
    }

    /**
     * @return string
     */
    public function getControllerNamespace()
    {
        // we will take values ​​from the routes file as namespace.
        // with the resolve method, route values ​​will come as an array.
        $fromRoutes         = Route::getRouteResolve();
        $namespace          = null;

        if(count($fromRoutes)){
            $fromRoutesClass    = (isset($fromRoutes['class'])) ? $fromRoutes['class'] : null;

            if($fromRoutes['namespace']===null){
                $namespace = app()->namespace()->controller().'\\'.$fromRoutesClass;
            }
            else{
                $namespace = app()->namespace()->controller().'\\'.$fromRoutes['namespace'].'\\'.$fromRoutesClass;
            }
        }

        //check namespace exists
        if(Utils::isNamespaceExists($namespace)){

            // the controller classes are registered in the config controller.
            // the controller class is not executed if it is not available here.
            //$this->checkConfigForController($namespace);
            return $namespace;
        }

        //Here we do the namespace control for the auto service. There is no endpoint available,
        //but if there is an auto service recognized by the system, this auto service will be the endpoint.
        return $this->checkAutoService($this);
    }

    /**
     * @param $instance
     * @return string
     */
    public function checkAutoService($instance)
    {
        //If auto service is present, this auto service will be accepted as endpoint namespace.
        return $this->checkEndpointForAutoService()->getNamespaceForAutoService($instance,function(){
            exception()->notFoundException('Any endpoint is not available');
        });
    }

    /**
     * @param $namespace
     */
    public function checkConfigForController($namespace)
    {
        $configController = config('controller');

        if($configController===null
            OR !isset($configController[$this->endpoint()])
            or !isset($configController[$this->endpoint()][$namespace])){
            exception()->badFunctionCall('The request has not been saved in your configuration settings.');
        }

        $configController = $configController[$this->endpoint()][$namespace];

        if(isset($configController[environment()]) and $configController[environment()]===false){
            exception()->domain('Sorry, this endpoint is not allowed to run for this environment.');
        }

        if(isset($configController['all']) AND $configController['all']===false){
            exception()->domain('Sorry, this endpoint is never allowed to run.');
        }
    }

    /**
     * @return mixed
     */
    public function checkEndpointForAutoService()
    {
        //Here we do the namespace control for the auto service. There is no endpoint available,
        //but if there is an auto service recognized by the system, this auto service will be the endpoint.
        return app()->resolve(CheckEndpointForAutoService::class);
    }
}