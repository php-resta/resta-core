<?php

namespace Resta\Container;

use Resta\Utils;
use Resta\Console\ConsoleBindings;
use Resta\Contracts\ContainerContracts;
use Resta\GlobalLoaders\KernelAssigner;
use Resta\GlobalLoaders\GlobalAssignerForBind;

class Container implements ContainerContracts {

    /**
     * @var $singleton
     */
    public $singleton=false;

    /**
     * @var $kernel
     */
    public $kernel;

    /**
     * @var array  $instance
     */
    private static $instance=[];

    /**
     * @var array $bindParams
     */
    private static $bindParams=[];

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
     * @return mixed
     */
    public function kernelAssigner(){

        //We will use the kernelAssigner class to resolve the singleton object state.
        return $this->makeBind(KernelAssigner::class);
    }

    /**
     * @method serviceContainerObject
     * @return void
     */
    private function serviceContainerObject(){

        //Since the objects that come to the build method are objects from the container method,
        //we need to automatically create a kernel object named serviceContainer in this method.
        $this->kernelAssigner()->container();
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
     * @param $eventName
     * @param $object
     * @return mixed
     */
    public function addEvent($eventName,$object){

        //Since the objects that come to the build method are objects from the container method,
        //we need to automatically create a kernel object named serviceContainer in this method.
        $this->kernelAssigner()->event();

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return $this->bind($eventName,$object,'container');

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
        $this->globalAssignerForBind($object,$callback);

        //the value corresponding to the bind value for the global object is assigned and
        //the makeBind method is called for the dependency injection.
        $this->kernelAssigner()->setKernelObject($object,$callback);

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
        $this->kernelAssigner()->consoleShared($object,$callback);
    }

    /**
     * @param $object
     * @param $callback
     */
    private function globalAssignerForBind($object,$callback){

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        $this->makeBind(GlobalAssignerForBind::class)->getAssigner($object,$callback);

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
        $this->kernelAssigner()->setKernelObject($object,$callback,'serviceContainer');

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

        //we automatically load a global loaders for the bind method
        //and assign it to the object name in the kernel object with bind,
        //which you can easily use in the booted classes for kernel object assignments.
        $this->globalAssignerForBind($object,$callback);

        //If the console object returns true,
        //we do not cancel binding operations
        //We are getting what applies to console with consoleKernelObject.
        if($this->console() AND $isCallableForCallback) return $this->consoleKernelObject($object,$container);

        //If the application is not a console operation, we re-bind to existing methods synchronously.
        return ($container) ? $this->build($object,$callback,true) : $this->make($object,$callback,true);
    }

    /**
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function makeBind($class,$bind=array()){

        //the context bind objects are checked again and the bind sequence submitted by
        //the user is checked and forced to re-instantiate the object.
        $this->contextualBindCleaner($class,$bind);

        //We do an instance check to get the static instance values of
        //the classes to be resolved with the makebind method.
        if(!isset(self::$instance[$class])){

            //bind params object
            self::$bindParams[$class]=$bind;

            //By singleton checking, we solve the dependency injection of the given class.
            //Thus, each class can be called together with its dependency.
            self::$instance[$class]=Utils::makeBind($class,$this->applicationProviderBinding($this,self::$bindParams[$class]));
            $this->singleton()->makeBind[class_basename($class)]=self::$instance[$class];

            //return makeBind class
            return self::$instance[$class];
        }

        //if the class to be resolved has already been loaded,
        //we get the instance value that was saved to get the recurring instance.
        return self::$instance[$class];

    }

    /**
     * @param $class
     * @param $bind
     */
    private function contextualBindCleaner($class,$bind){

        //the context bind objects are checked again and the bind sequence submitted by
        //the user is checked and forced to re-instantiate the object.
        if(isset(self::$instance[$class]) && self::$bindParams[$class]!==$bind){
            unset(self::$instance[$class]);
            unset(self::$bindParams[$class]);
        }
    }


    /**
     * @param $make
     * @param array $bind
     * @return array
     */
    public function applicationProviderBinding($make,$bind=array()){

        //service container is an automatic application provider
        //that we can bind to the special class di in the dependency condition.
        //This method is automatically added to the classes resolved by the entire makebind method.
        return array_merge($bind,['app'=>$make]);
    }



}