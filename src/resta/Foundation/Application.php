<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\ApplicationHelpersContracts;
use Resta\StaticPathModel;
use Resta\Traits\ApplicationPath;
use Resta\Utils;

class Application extends Kernel implements ApplicationContracts,ApplicationHelpersContracts {

    //get app paths
    use ApplicationPath;

    /**
     * @var array  $instance
     */
    private static $instance=[];

    /**
     * @var $console null
     */
    protected $console;

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

        //we can refer to this method
        //because we can boot classes in the middleware array.
        return $this->middlewareGroups;
    }

    /**
     * @return array
     */
    public function getBootstrappers(){

        //we can refer to this method
        //because we can boot classes in the bootstrappers array.
        return $this->bootstrappers;
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
        return array_merge(['app'=>$make],$bind);
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

            //By singleton checking, we solve the dependency injection of the given class.
            //Thus, each class can be called together with its dependency.
            self::$instance[$class]=Utils::makeBind($class,$this->applicationProviderBinding($this));
            return self::$instance[$class];
        }

        //if the class to be resolved has already been loaded,
        //we get the instance value that was saved to get the recurring instance.
        return self::$instance[$class];

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

}