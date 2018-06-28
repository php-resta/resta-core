<?php

namespace Resta\GlobalLoaders;

use Resta\Utils;
use Resta\ApplicationProvider;

class KernelAssigner extends ApplicationProvider  {

    /**
     * @return mixed|void
     */
    public function container(){

        //We are initializing the array property for the service container object.
        if(!isset($this->singleton()->serviceContainer)){
            $this->register('serviceContainer',[]);
        }
    }

    /**
     * @param $object
     * @param $callback
     * @return mixed|void
     */
    public function consoleShared($object,$callback){

        //The console share is evaluated as a true variable to be assigned as the 3rd parameter in the classes to be bound.
        //The work to be done here is to bind the classes to be included in the console share privately.
        if($this->app->console()){
            $this->register('consoleShared',$object,$this->getConcrete($callback));
        }
    }

    /**
     * @param $object
     * @param $concrete
     */
    public function setKernelObject($object,$concrete,$value=null){

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=$this->getConcrete($concrete);

        //the value is directly assigned to the kernel object.
        //The value is moved throughout the application in the kernel of the application object.
        if($value===null){
            $this->setKernel($object,$concrete);
        }

        //The service container value is moved differently from the value directly assigned to the kernel object.
        //The application container is booted directly with the service container custom class
        //in the version section of the your application.
        if($value==="serviceContainer"){
            $this->setServiceContainer($object,$concrete);
        }

    }

    /**
     * @param $concrete
     * @return mixed
     */
    private function getConcrete($concrete){

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        return Utils::callbackProcess($concrete);
    }

    /**
     * @param $object
     * @param $concrete
     */
    private function setKernel($object,$concrete){

        //We check that the concrete object
        //is an object that can be retrieved.
        if(!isset($this->singleton()->{$object}) && class_exists($concrete)){

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency injection.
            $this->singleton()->{$object}=$this->makeBind($concrete)->handle();
        }
    }

    /**
     * @param $object
     * @param $concrete
     */
    private function setServiceContainer($object,$concrete){

        //We check that the concrete object
        //is an object that can be retrieved.
        if(isset($this->singleton()->serviceContainer) && !isset($this->singleton()->serviceContainer[$object])){

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency method.
            $this->register('serviceContainer',$object,$concrete);
        }
    }

}