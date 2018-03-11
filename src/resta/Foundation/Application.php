<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;

class Application extends Kernel implements ApplicationContracts {

    /**
     * @var $boot
     */
    public $boot=false;

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
     * @method devEagerConfiguration
     * @return void
     */
    public function devEagerConfiguration(){

        //kernel eager for dev
        $this->devEagers($this);
    }

    /**
     * @method booting
     * @return void
     */
    public function booting(){

        //check boot for only once
        //if boot is true,booting classes would not run
        if($this->boot){
            return;
        }

        //system booting for app
        //pre-loaders are the most necessary classes for the system.
        $this->bootstrappers($this);

        //boot true
        $this->boot=true;
    }

    /**
     * @method middleware
     * @return void
     */
    public function middleware(){

        //pre-loaders user-based
        $this->middlewareLoaders($this);
    }


    /**
     * @method handle
     * @return string
     */
    public function handle(){

        return ($this->console) ? $this->kernel->console : $this->kernel->response;
    }

    /**
     * @param $boot
     * @return mixed
     */
    public function bootFire($boot){

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
}