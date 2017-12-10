<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;
use Resta\GlobalLoaders\Route;
use Resta\Traits\NamespaceForRoute;
use Resta\Utils;

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
        return $this->app->kernel()->serviceDummy;
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
        $this->makeBind(Route::class)->route($unset);
    }


    /**
     * @method controllerMethodProcess
     * @return mixed
     */
    private function controllerMethodProcess(){

        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return ($this->app->kernel()->serviceConf['dummy']) ? $this->checkDummy() : $this->getCallBindController();
    }

    /**
     * @method getCallBindController
     * @return mixed
     */
    private function getCallBindController(){

        //we finally process the method of the class invoked by the user as a process and prepare it for the response
        return Utils::callBind([$this->instanceController(),$this->checkIfExistsMethod()],$this->providerBinding());
    }

    /**
     * @method checkDummy
     * @return mixed
     */
    private function checkDummy(){

        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return (false===$this->diffDummyAndController()) ? $this->serviceDummy() : $this->getCallBindController();

    }

    /**
     * @method diffDummyAndController
     * @return bool
     */
    private function diffDummyAndController(){

        //We check the equality of the dummy data with the controller method data.
        return Utils::isArrayEqual($this->getCallBindController(),$this->serviceDummy());
    }




}