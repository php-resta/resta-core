<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ContainerKernelAssigner extends ApplicationProvider
{
    /**
     * @param $object
     * @param $callback
     * @return void
     */
    public function consoleShared($object,$callback)
    {
        //The console share is evaluated as a true variable to be assigned as the 3rd parameter in the classes to be bound.
        //The work to be done here is to bind the classes to be included in the console share privately.
        if($this->app->console()){
            $this->app->register('consoleShared',$object,$this->getConcrete($callback));
        }
    }

    /**
     * @return mixed|void
     */
    public function container()
    {
        //We are initializing the array property for the service container object.
        if(!isset($this->app['serviceContainer'])){
            $this->app->register('serviceContainer',[]);
        }
    }

    /**
     * @param $concrete
     * @return mixed
     */
    private function getConcrete($concrete)
    {
        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        return Utils::callbackProcess($concrete);
    }

    /**
     * @param $object
     * @param $concrete
     * @return void
     */
    private function setKernel($object,$concrete)
    {
        //We check that the concrete object
        //is an object that can be retrieved.
        if(!isset($this->app[$object]) && class_exists($concrete)){

            //get concrete instance
            $concreteInstance = $this->app->resolve($concrete);

            //the value corresponding to the bind value for the global object is assigned and
            //the resolve method is called for the dependency injection.
            $this->app->register($object,$concreteInstance);

            // this method is executed if the concrete instance contains the handle method.
            // if no handle method is included, the concrete instance is returned directly.
            (method_exists($concreteInstance,'handle'))
                ? $concreteInstance->handle()
                : $concreteInstance;

        }
    }

    /**
     * @param $object
     * @param object $concrete
     * @param null|string $value
     * @return void
     */
    public function setKernelObject($object,$concrete,$value=null)
    {
        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete = $this->getConcrete($concrete);

        //the value is directly assigned to the kernel object.
        //The value is moved throughout the application in the kernel of the application object.
        if(is_null($value)){
            $this->setKernel($object,$concrete);
        }

        //The service container value is moved differently from the value directly assigned to the kernel object.
        //The application container is booted directly with the service container custom class
        //in the version section of the your application.
        if($value==="serviceContainer"){
            $this->setServiceContainer($object,$concrete);
        }

        // we will resolve the value of concrete according to whether it is an object.
        $detectorBindings = (is_object($concrete)) ? $concrete : Utils::resolverClass($concrete);

        //we register the bound object to the kernel bindings property.
        $this->app->register('bindings',$object,$detectorBindings);

    }

    /**
     * @param $object
     * @param $concrete
     * @return void
     */
    private function setServiceContainer($object,$concrete)
    {
        //We check that the concrete object
        //is an object that can be retrieved.
        if(isset($this->app['serviceContainer']) && !isset($this->app['serviceContainer'][$object])){

            //the value corresponding to the bind value for the global object is assigned and
            //the resolve method is called for the dependency method.
            $this->app->register('serviceContainer',$object,$concrete);
        }
        else{

            // the service container objects are saved only once.
            // Overflow exception is thrown on multiple records.
            exception()->overflow('The "'.$object.'" object was previously saved into service container.');
        }
    }
}