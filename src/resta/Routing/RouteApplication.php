<?php

namespace Resta\Routing;

use Resta\Support\Utils;
use Resta\ApplicationProvider;
use Resta\GlobalLoaders\Router;
use Resta\Traits\NamespaceForRoute;

class RouteApplication extends ApplicationProvider
{
    //get namespace for route
    use NamespaceForRoute;

    /**
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function callController()
    {
        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the rest system.
        //Objects in this class are destroyed when their work is finished.
        $this->singletonEagerForRoute();

        //call service together with controller method
        return $this->getCallBindController();
    }

    /**
     * @method getCallBindController
     * @return mixed
     */
    private function getCallBindController()
    {
        //we finally process the method of the class invoked by the user as a process and prepare it for the response
        return app()->makeBind(RouteWatch::class)->watch(function(){
            if(method_exists($this->instanceController(),resta()->method)){
                return Utils::callBind([$this->instanceController(),resta()->method],$this->providerBinding());
            }
            exception()->badMethodCall('The name of the method to be executed does not exist in the object.');
        });
    }

    /**
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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