<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;
use Resta\Traits\NamespaceForRoute;
use Resta\Utils;

class RouteApplication extends ApplicationProvider {

    //get namespace for route
    use NamespaceForRoute;

    /**
     * @var $url
     */
    public $url;

    /**
     * @method handle
     * @return mixed
     */
    public function handle(){

        //we assign the url object to the global kernel url object
        //so that it can be read anywhere in our route file.
        $this->url=$this->app->kernel()->url;

        //we call our services as controller
        return $this->callController();

    }

    /**
     * @method callController
     * @return mixed
     */
    private function callController(){

        //get controller namespace
        $controller=$this->getControllerNamespace();

        //utils make bind via dependency injection named as service container
        $this->app->singleton()->instanceController=$this->makeBind($controller);

        //call service together with controller method
        return $this->controllerMethodProcess($this->instanceController());
    }

    /**
     * @method instanceController
     * @return mixed
     */
    public function instanceController(){

        return $this->app->kernel()->instanceController;
    }


    /**
     * @method controllerMethodProcess
     * @param $controller
     * @return mixed
     */
    private function controllerMethodProcess($controller){

        return Utils::callBind([$controller,$this->checkIfExistsMethod()],$this->providerBinding());
    }

}