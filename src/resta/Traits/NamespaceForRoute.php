<?php

namespace Resta\Traits;

use Resta\GlobalLoaders\Route;
use Resta\Routing\CheckEndpointForAutoService;
use Resta\StaticPathModel;
use Resta\Utils;

trait NamespaceForRoute {

    /**
     * @return mixed
     */
    public function project(){

        return $this->url['project'];
    }


    /**
     * @return mixed
     */
    public function namespace(){

        return $this->url['namespace'];
    }


    /**
     * @return mixed
     */
    public function endpoint(){

        return $this->url['endpoint'];
    }

    /**
     * @return mixed
     */
    public function urlMethod(){

        return $this->url['method'];
    }


    /**
     * @return mixed
     */
    public function method(){

        return strtolower($this->httpMethod()).''.ucfirst($this->url['method']);
    }

    /**
     * @param $method
     * @method resolveMethod
     */
    public function resolveMethod($method){

        return str_replace($this->httpMethod(),'',$method);
    }

    /**
     * @return string
     */
    public function autoService(){

        return StaticPathModel::getAutoServiceNamespace().'\\'.$this->endpoint().'\\'.$this->endpoint().''.StaticPathModel::$callClassPrefix;
    }

    /**
     * @return mixed
     */
    public function param(){

        return $this->url['param'];
    }


    /**
     * @return string
     */
    public function getPrefixMethod(){

        return ucfirst($this->method()).''.StaticPathModel::$methodPrefix;
    }

    /**
     * @return string
     */
    public function getPrefixIndexMethod(){

        //by default we assign a default method value of indexAction
        //this value must be a method value automatically named indexAction
        //if it does not come from the url discovery value
        return $this->httpMethod().'Index'.StaticPathModel::$methodPrefix;
    }

    /**
     * @method checkIfExistsMethod
     * @return mixed
     */
    public function checkIfExistsMethod(){

        //If controller does not have a method after checking whether the method specified in the controller class exists,
        //then by default we assign a default method value of indexAction.
        //This value must match the class strictly, otherwise the controller can not be called
        if(method_exists($this->instanceController(),$this->getPrefixMethod())){

            //method value as normal
            return $this->getPrefixMethod();
        }

        //by default we assign a default method value of indexAction
        //this value must be a method value automatically named indexAction
        //if it does not come from the url discovery value
        return $this->getPrefixIndexMethod();

    }

    /**
     * @param $method
     * @method routeParametersAssign
     * @return mixed
     */
    public function routeParametersAssign($method){

        //If the path variables give values ​​in the methods do we have a method name,
        //we subtract this value from the route variables.
        return array_values(array_diff($this->routeParameters(),[$method]));

    }


    /**
     * @return mixed
     */
    public function getControllerNamespace(){

        //generator namespace for array
        $namespace=Utils::generatorNamespace([

            //composer autoload namespace
            StaticPathModel::$autoloadNamespace,

            //project name
            $this->project(),

            //project version name
            Utils::getAppVersion($this->project()),

            //controller static path name
            StaticPathModel::$controller,

            //endpoint name
            $this->endpoint(),

            //call file
            $this->endpoint().''.StaticPathModel::$callClassPrefix
        ]);

        //check namespace exists
        if(Utils::isNamespaceExists($namespace)){
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
    public function checkAutoService($instance){

        //If auto service is present, this auto service will be accepted as endpoint namespace.
        return $this->checkEndpointForAutoService()->getNamespaceForAutoService($instance,function(){
            throw new \LogicException('Any endpoint is not available');
        });
    }

    /**
     * @return \Resta\Routing\CheckEndpointForAutoService
     */
    public function checkEndpointForAutoService(){

        //Here we do the namespace control for the auto service. There is no endpoint available,
        //but if there is an auto service recognized by the system, this auto service will be the endpoint.
        return $this->makeBind(CheckEndpointForAutoService::class);
    }



}