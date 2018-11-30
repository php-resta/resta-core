<?php

namespace Resta\Traits;

use Resta\Utils;
use Resta\StaticPathList;
use Resta\StaticPathModel;
use Resta\GlobalLoaders\Router;
use Resta\Routing\CheckEndpointForAutoService;

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
        return $this->url['namespace'];
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
        return strtolower($this->httpMethod()).''.ucfirst($this->url['method']);
    }

    /**
     * @param $method
     * @return mixed
     */
    public function resolveMethod($method)
    {
        $method=str_replace($this->httpMethod(),'',$method);
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
        return $this->url['param'];
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
        return $this->httpMethod().'Index'.StaticPathModel::$methodPrefix;
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
        if(method_exists($this->instanceController(),$this->getPrefixMethod())){

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
        return array_values(array_diff($this->routeParameters(),[$method]));
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
        $deleteHttp         = str_replace($this->httpMethod(),'',$method);
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
        //generator namespace for array
        $namespace=app()->namespace()->controller($this->endpoint(),true);

        //check namespace exists
        if(file_exists(Utils::getPathFromNamespace($namespace)) && Utils::isNamespaceExists($namespace)){
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
            throw new \UnexpectedValueException('Any endpoint is not available');
        });
    }

    /**
     * @return mixed
     */
    public function checkEndpointForAutoService()
    {
        //Here we do the namespace control for the auto service. There is no endpoint available,
        //but if there is an auto service recognized by the system, this auto service will be the endpoint.
        return $this->makeBind(CheckEndpointForAutoService::class);
    }
}