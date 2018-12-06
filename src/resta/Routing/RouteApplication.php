<?php

namespace Resta\Routing;

use Resta\Utils;
use Resta\ApplicationProvider;
use Resta\GlobalLoaders\Router;
use Resta\Traits\NamespaceForRoute;

class RouteApplication extends ApplicationProvider
{
    //get namespace for route
    use NamespaceForRoute;

    /**
     * @method callController
     * @return mixed
     */
    private function callController()
    {
        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the rest system.
        //Objects in this class are destroyed when their work is finished.
        $this->singletonEagerForRoute();

        //call service together with controller method
        return $this->controllerMethodProcess();
    }

    /**
     * @method checkDummy
     * @return mixed
     */
    private function checkDummy()
    {
        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return (false===$this->diffDummyAndController()) ?
            array_merge($this->serviceDummy(),['__serviceDummy'=>true]) : $this->getCallBindController();
    }

    /**
     * @method controllerMethodProcess
     * @return mixed
     */
    private function controllerMethodProcess()
    {
        //In this case, the dummy object is checked as bool in the service conf object
        //and the equation is compared with the returned value.
        //If the boolean value is true at the end of the comparison, the dummy data screen is printed
        return (resta()->serviceConf['dummy']) ? $this->checkDummy() : $this->getCallBindController();
    }

    /**
     * @method diffDummyAndController
     * @return bool
     */
    private function diffDummyAndController()
    {
        //We check the equality of the dummy data with the controller method data.
        return Utils::array_diff_key_recursive($this->serviceDummy(),$this->getCallBindController());
    }

    /**
     * @method getCallBindController
     * @return mixed
     */
    private function getCallBindController()
    {
        //we finally process the method of the class invoked by the user as a process and prepare it for the response
        return app()->makeBind(RouteWatch::class)->watch(function(){
            return Utils::callBind([$this->instanceController(),resta()->method],$this->providerBinding());
        });
    }

    /**
     * @method handle
     * @return mixed
     */
    public function handle()
    {
        //we call our services as controller
        return $this->callController();
    }

    /**
     * @method instanceController
     * @return mixed
     */
    public function instanceController()
    {
        //The kernel object
        //we temporarily assigned on the instance of the class obtained by route
        return resta()->instanceController;
    }

    /**
     * @method serviceDummy
     * @return mixed
     */
    public function serviceDummy()
    {
        //The kernel object
        //we temporarily assigned on the instance of the class obtained by route
        return resta()->serviceDummy[strtolower(method)];
    }

    /**
     * @return void
     */
    private function singletonEagerForRoute()
    {
        /**
         * get global router instance
         * @var $routerGlobalInstance Router
         */
        $routerGlobalInstance=resta()->routerGlobalInstance;

        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the rest system.
        //Objects in this class are destroyed when their work is finished.
        $routerGlobalInstance->route();

        //we update the existing route parameter to make a new assignment on
        //the kernel object to extract the method name from the original route parameters.
        $routerGlobalInstance->substractMethodNameFromRouteParameters($this->checkIfExistsMethod($routerGlobalInstance));
    }
}