<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;
use Resta\Utils;

class Application extends Kernel implements ApplicationContracts {

    /**
     * @var array  $instance
     */
    private static $instance=[];

    /**
     * @var $console null
     */
    public $console;

    /**
     * Application constructor.
     * @param bool $console
     * @return void
     */
    public function __construct($console=false){

        //get console status for cli
        $this->console=$console;

        //The bootstrapper method is the initial process
        //that runs the individual methods that the application initiated.
        new Bootstrappers($this);
    }

    /**
     * @method handle
     * @return string
     */
    public function handle(){

        //This is the main calling place of your application.
        //If you come via http, the kernel response value is evaulated.
        //If you come via console, the kernel console value is evaulated.
        return ($this->console) ? $this->kernel->console : $this->kernel->response;
    }

    /**
     * @param $boot
     * @return mixed
     */
    protected function bootFire($boot){

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        return forward_static_call_array([array_pop($boot),'loadBootstrappers'],[$boot]);
    }

    /**
     * @return array
     */
    public function getMiddlewareGroups(){

        return $this->middlewareGroups;
    }

    /**
     * @return array
     */
    public function getBootstrappers(){

        return $this->bootstrappers;
    }

    /**
     * @param $make
     * @param array $bind
     * @return array
     */
    public function applicationProviderBinding($make,$bind=array()){

        $default=['app'=>$make];
        return array_merge($default,$bind);
    }

    /**
     * @method $class
     * @param $class
     * @return mixed
     */
    public function makeBind($class){

        //We do an instance check to get the static instance values of
        //the classes to be resolved with the makebind method.
        if(!isset(self::$instance[$class])){
            self::$instance[$class]=Utils::makeBind($class,$this->applicationProviderBinding($this));
            return self::$instance[$class];
        }

        //if the class to be resolved has already been loaded,
        //we get the instance value that was saved to get the recurring instance.
        return self::$instance[$class];

    }



}