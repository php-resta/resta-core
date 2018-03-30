<?php

namespace Resta\Foundation;

use Resta\Console\ConsoleBindings;
use Resta\Contracts\ApplicationContracts;
use Resta\GlobalLoaders\GlobalAssignerForBind;
use Resta\GlobalLoaders\KernelAssigner;
use Resta\Utils;

class Container implements ApplicationContracts {

    /**
     * @var $singleton
     */
    public $singleton=false;

    /**
     * @var $kernel
     */
    public $kernel;

    /**
     * @return mixed
     */
    public function kernel(){

        //The kernel object system is the container backbone.
        //Binding binding and container loads are done with
        //the help of this object and distributed to the system.
        return $this->kernel;
    }

    /**
     * @method console
     * @return bool|null
     */
    public function console(){

        //Controlling the console object is
        //intended to make sure that the kernel bootstrap classes do not work.
        return $this->console;
    }

    /**
     * @method serviceContainerObject
     * @return void
     */
    private function serviceContainerObject(){

        //Since the objects that come to the build method are objects from the container method,
        //we need to automatically create a kernel object named serviceContainer in this method.
        $this->makeBind(KernelAssigner::class)->container();
    }

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @param $container false|true
     * @return mixed
     */
    public function bind($object=null,$callback=null,$container=false){

        //we check whether the boolean value of the singleton variable used
        //for booting does not reset every time the object variable to be assigned to the kernel variable is true
        $this->singleton();

        //The console share is evaluated as a true variable to be assigned as the 3rd parameter in the classes to be bound.
        //The work to be done here is to bind the classes to be included in the console share privately.
        if($container){
            $this->consoleShared($object,$callback);
        }

        //If the third parameter passed to the bind method carries a container value,
        //then you will not be able to fire the build method instead of the make method.
        $makeBuild=($container==="container") ? 'build' : 'make';

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return ($object===null) ? $this->kernel() : $this->{$makeBuild}($object,$callback);

    }

    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function container($object=null,$callback=null){

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return $this->bind($object,$callback,'container');

    }

    /**
     * @method singleton
     */
    public function singleton(){

        if($this->singleton===false){

            //after first initializing, the singleton variable is set to true,
            //and subsequent incoming classes can inherit the loaded object.
            $this->singleton=true;
            $this->kernel=\application::kernelBindObject();
        }

        //kernel object taken over
        return $this->kernel();
    }

    /**
     * @method make
     * @param $object
     * @param $callback
     * @param $sync false
     * @return mixed
     */
    private function make($object,$callback,$sync=false){

        //If the console object returns true,
        //we do not cancel binding operations
        //We are getting what applies to console with consoleKernelObject.
        if($sync===false) return $this->consoleKernelObjectChecker($object,$callback);

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        $this->globalAssignerForBind($object);

        //the value corresponding to the bind value for the global object is assigned and
        //the makeBind method is called for the dependency injection.
        $this->makeBind(KernelAssigner::class)->setKernelObject($object,$callback);

        //return kernel object
        return $this->kernel();
    }

    /**
     * @param $object
     * @param bool $container
     * @return mixed
     */
    private function consoleKernelObject($object,$container=false){

        //we use the console bindings class to specify the classes to be preloaded in the console application.
        //Thus, classes that can not be bound with http are called without closure in global loaders directory.
        $this->makeBind(ConsoleBindings::class)->console($object,$container);

        //The console application must always return the kernel method.
        return $this->kernel();
    }

    /**
     * @param $object
     * @param $callback
     */
    private function consoleShared($object,$callback){

        //The console share is evaluated as a true variable to be assigned as the 3rd parameter in the classes to be bound.
        //The work to be done here is to bind the classes to be included in the console share privately.
        $this->makeBind(KernelAssigner::class)->consoleShared($object,$callback);
    }

    /**
     * @method globalAssignerForBind
     * @param $object
     * @return mixed
     */
    private function globalAssignerForBind($object){

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        $this->makeBind(GlobalAssignerForBind::class)->getAssigner($object);

    }

    /**
     * @method build
     * @param $object
     * @param $callback
     * @param $sync false
     * @return mixed
     */
    public function build($object,$callback,$sync=false){

        //If the console object returns true,
        //we do not cancel binding operations
        //We are getting what applies to console with consoleKernelObject.
        if($sync===false) return $this->consoleKernelObjectChecker($object,$callback,true);

        //Since the objects that come to the build method are objects from the container method,
        //we need to automatically create a kernel object named serviceContainer in this method.
        $this->serviceContainerObject();

        //the value corresponding to the bind value for the global object is assigned and
        //the makeBind method is called for the dependency method.
        $this->makeBind(KernelAssigner::class)->setKernelObject($object,$callback,'serviceContainer');

        //return kernel object
        return $this->kernel();
    }

    /**
     * @param $object
     * @param $callback
     * @param bool $container
     */
    private function consoleKernelObjectChecker($object,$callback,$container=false){

        //we check whether the callback value is a callable function.
        $isCallableForCallback=is_callable($callback);

        //If the console object returns true,
        //we do not cancel binding operations
        //We are getting what applies to console with consoleKernelObject.
        if($this->console() AND $isCallableForCallback) return $this->consoleKernelObject($object,$container);

        //If the application is not a console operation, we re-bind to existing methods synchronously.
        return ($container) ? $this->build($object,$callback,true) : $this->make($object,$callback,true);
    }



}