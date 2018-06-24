<?php

namespace Resta\Routing;

use Resta\Utils;
use Resta\GlobalLoaders\Route;
use Resta\ApplicationProvider;
use Resta\Traits\NamespaceForRoute;

class RouteApplication extends ApplicationProvider {

    //get namespace for route
    use NamespaceForRoute;

    /**
     * @method handle
     * @return mixed
     */
    public function handle(){

        //we call our services as controller
        return $this->callController();

    }

    /**
     * @method callController
     * @return mixed
     */
    private function callController(){

        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the resta.
        //Objects in this class are destroyed when their work is finished.
        $this->singletonEagerForRoute();

        //call service together with controller method
        return $this->controllerMethodProcess();
    }

    /**
     * @method instanceController
     * @return mixed
     */
    public function instanceController(){

        //The kernel object
        //we temporarily assigned on the instance of the class obtained by route
        return $this->app->kernel()->instanceController;
    }


    /**
     * @method serviceDummy
     * @return mixed
     */
    public function serviceDummy(){

        //The kernel object
        //we temporarily assigned on the instance of the class obtained by route
        return $this->app->kernel()->serviceDummy[strtolower(method)];
    }

    /**
     * @method singletonEagerForRoute
     * @param $unset false
     * return mixed
     */
    private function singletonEagerForRoute($unset=false){

        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the resta.
        //Objects in this class are destroyed when their work is finished.
        $this->singleton()->routerGlobalInstance->route($unset);

        //we update the existing route parameter to make a new assignment on
        //the kernel object to extract the method name from the original route parameters.
        $this->singleton()->routerGlobalInstance->substractMethodNameFromRouteParameters($this->checkIfExistsMethod());

    }


    /**
     * @method controllerMethodProcess
     * @return mixed
     */
    private function controllerMethodProcess(){

        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return ($this->singleton()->serviceConf['dummy']) ? $this->checkDummy() : $this->getCallBindController();
    }

    /**
     * @method getCallBindController
     * @return mixed
     */
    private function getCallBindController(){

        //we finally process the method of the class invoked by the user as a process and prepare it for the response
        return app()->makeBind(RouteWatch::class)->watch(function(){
            return Utils::callBind([$this->instanceController(),$this->checkIfExistsMethod()],$this->providerBinding());
        });

    }

    /**
     * @method checkDummy
     * @return mixed
     */
    private function checkDummy(){

        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return (false===$this->diffDummyAndController()) ?
            array_merge($this->serviceDummy(),['__serviceDummy'=>true]) : $this->getCallBindController();

    }

    /**
     * @method diffDummyAndController
     * @return bool
     */
    private function diffDummyAndController(){

        //We check the equality of the dummy data with the controller method data.
        return Utils::array_diff_key_recursive($this->serviceDummy(),$this->getCallBindController());
    }




}