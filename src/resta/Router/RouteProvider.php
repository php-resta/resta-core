<?php

namespace Resta\Router;

use Closure;
use Resta\Traits\NamespaceForRoute;
use Resta\Container\DIContainerManager;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathModel;

class RouteProvider extends ApplicationProvider
{
    //get namespace for route
    use NamespaceForRoute;

    /**
     * resolving event fire for response
     *
     * @param null $event
     * @param bool $return
     * @return mixed|void
     */
    private function fireEvent($event=null,$return=false)
    {
        // if an array of response-events is registered
        // in the container system, the event will fire.
        if($this->app->has('response-event.'.$event)){
            $event = $this->app->get('response-event.'.$event);

            // the event to be fired must be
            // a closure instance.
            if($event instanceof Closure){
                $eventResolved = $event($this->app);

                if($return){
                    return $eventResolved;
                }
            }
        }
    }

    /**
     * call controller for routing
     *
     * @return mixed
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function callController()
    {
        $this->fireEvent('before',true);

        if($this->app->has('output')){
            return $this->app->get('output');
        }

        if(is_array($event = $this->fireEvent('output',true))){
            $controller = $event;
        }
        else{

            //the singleton eager class is a class built to temporarily prevent
            //the use of user-side kernel objects used by the rest system.
            //Objects in this class are destroyed when their work is finished.
            $this->singletonEagerForRoute();

            //call service together with controller method
            $controller = $this->getCallBindController();
        }

        $this->app->register('output',$controller);

        return $controller;
    }

    /**
     * get call bind controller
     *
     * @return mixed
     */
    private function getCallBindController()
    {
        //we finally process the method of the class invoked by the user as a process and prepare it for the response
        return app()->resolve(RouteWatch::class)->watch(function(){

            // if the method in the instance object exists,
            // this method is executed to produce the output.
            if(method_exists($this->app['instanceController'],$this->app['method'])){
                return $this->app['di']($this->app['instanceController'],$this->app['method']);
            }

            //throw exception as unsuccessful
            exception()->badMethodCall('The name of the method to be executed does not exist in the object.');
        });
    }

    /**
     * route application handle
     *
     * @return mixed
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function handle()
    {
        $this->app->register('routerResult',$this->callController());

        //we call our services as controller
        return $this->app['routerResult'];
    }

    /**
     * get route
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function route()
    {
        $namespace = $this->getControllerNamespace();

        //utils make bind via dependency injection named as service container
        $this->app->register('serviceConf',$this->app['fileSystem']->callFile(StaticPathModel::getServiceConf()));
        $this->app->register('instanceController',$this->app->resolve($namespace));
    }

    /**
     * route service configuration
     *
     * @param $parameters
     * @return void
     */
    public function routeServiceConfiguration($parameters)
    {
        // we record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method = strtolower($this->app['urlComponent']['method']);

        // based on the serviceConf variable,
        // we are doing parameter bindings in the method context in the routeParameters array key.
        $this->app->register('serviceConf','routeParameters',[$method=>$parameters]);

    }

    /**
     * set method name via define
     *
     * @param $methodName
     * @return void|mixed
     */
    public function setMethodNameViaDefine($methodName)
    {
        define('methodName',strtolower($methodName));
    }


    /**
     * singleton eager for route
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function singletonEagerForRoute()
    {
        //the singleton eager class is a class built to temporarily prevent
        //the use of user-side kernel objects used by the rest system.
        //Objects in this class are destroyed when their work is finished.
        $this->route();

        //we update the existing route parameter to make a new assignment on
        //the kernel object to extract the method name from the original route parameters.
        $this->substractMethodNameFromRouteParameters($this->checkIfExistsMethod($this));
    }

    /**
     * @param $method
     * @return void
     */
    public function substractMethodNameFromRouteParameters($method)
    {
        $fromRoutes = Route::getRouteResolve();
        $method = $fromRoutes['method'] ?? $method;

        $this->app->register('method',$method);
        $this->app->register('routeParameters', $this->routeParametersAssign($this->resolveMethod($method)));

    }
}