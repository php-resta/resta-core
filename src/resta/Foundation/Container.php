<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;
use Resta\GlobalLoaders\GlobalAssignerForBind;
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
     * @var $console null
     */
    public $console;

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
        if(!isset($this->kernel()->serviceContainer)){
            $this->kernel()->serviceContainer=[];
        }
    }

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function bind($object=null,$callback=null){

        //we check whether the boolean value of the singleton variable used
        //for booting does not reset every time the object variable to be assigned to the kernel variable is true
        $this->singleton();

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return ($object===null) ? $this->kernel() : $this->make($object,$callback);

    }


    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function container($object=null,$callback=null){

        //we check whether the boolean value of the singleton variable used
        //for booting does not reset every time the object variable to be assigned to the kernel variable is true
        $this->singleton();

        //If the bind method does not have parameters object and callback, the value is directly assigned to the kernel object.
        //Otherwise, when the bind object and callback are sent, the closure class inherits
        //the applicationProvider object and the makeBind method is called
        return ($object===null) ? $this->kernel() : $this->build($object,$callback);

    }

    /**
     * @method singleton
     */
    public function singleton(){

        if($this->singleton===false){

            //after first initializing, the singleton variable is set to true,
            //and subsequent incoming classes can inherit the loaded object.
            $this->singleton=true;
            $this->kernel=new \stdClass;
        }

        //kernel object taken over
        return $this->kernel();
    }

    /**
     * @method make
     * @param $object
     * @param $callback
     * @return mixed
     */
    private function make($object,$callback){

        //If the console object returns true,
        //we do not cancel binding operations
        if($this->console()) return $this->kernel();

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=call_user_func($callback);

        //We check that the concrete object
        //is an object that can be retrieved.
        if(!isset($this->kernel()->{$object}) && class_exists($concrete)){

            //we automatically load a global loaders for the bind method
            //and assign it to the object name in the kernel object with bind,
            //which you can easily use in the booted classes for kernel object assignments.
            $this->globalAssignerForBind($object);

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency injection.
            $this->kernel()->{$object}=Utils::makeBind($concrete,$this->applicationProviderBinding($this))
                ->handle();
        }

        //return kernel object
        return $this->kernel();
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
        Utils::makeBind(GlobalAssignerForBind::class,$this->applicationProviderBinding($this))->getAssigner($object);

    }


    /**
     * @method build
     * @param $object
     * @param $callback
     * @return mixed
     */
    private function build($object,$callback){

        //If the console object returns true,
        //we do not cancel binding operations
        if($this->console()) return $this->kernel();

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=call_user_func($callback);

        //Since the objects that come to the build method are objects from the container method,
        //we need to automatically create a kernel object named serviceContainer in this method.
        $this->serviceContainerObject();

        //We check that the concrete object
        //is an object that can be retrieved.
        if(isset($this->kernel()->serviceContainer) && !isset($this->kernel()->serviceContainer[$object])){

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency method.
            $this->kernel()->serviceContainer[$object]=$concrete;
        }

        //return kernel object
        return $this->kernel();
    }


    /**
     * @param $make
     * @return array
     */
    public function applicationProviderBinding($make){

        return [
            'app'=>$make
        ];
    }
}