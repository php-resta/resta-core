<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;
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

        //get kernel object
        return $this->kernel;
    }

    /**
     * @method console
     * @return bool|null
     */
    public function console(){

        return $this->console;
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
     * @method service
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function service($object=null,$callback=null){

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
    public function make($object,$callback){

        //If the console object returns true,
        //we do not cancel binding operations
        if($this->console()) return $this->kernel();

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=call_user_func($callback);

        //We check that the concrete object
        //is an object that can be retrieved.
        if(!isset($this->kernel()->{$object}) && class_exists($concrete)){

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency injection.
            $this->kernel()->{$object}=Utils::makeBind($concrete,$this->applicationProviderBinding($this))
                ->handle();
        }

        //return kernel object
        return $this->kernel();
    }


    /**
     * @method build
     * @param $object
     * @param $callback
     * @return mixed
     */
    public function build($object,$callback){

        //If the console object returns true,
        //we do not cancel binding operations
        if($this->console()) return $this->kernel();

        //if a pre loader class wants to have before kernel values,
        //it must return a callback to the bind method
        $concrete=call_user_func($callback);

        //We check that the concrete object
        //is an object that can be retrieved.
        if(!isset($this->kernel()->{$object}) && !class_exists($concrete)){

            //the value corresponding to the bind value for the global object is assigned and
            //the makeBind method is called for the dependency method.
            $this->kernel()->{$object}=$concrete;
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