<?php

namespace Resta\Foundation;

use Resta\Contracts\ApplicationContracts;
use Resta\Traits\ApplicationTraits;

class Application extends Kernel implements ApplicationContracts {

    //get application traits
    use ApplicationTraits;

    /**
     * @var $environment null
     */
    public $environment;

    /**
     * @var $console null
     */
    public $console;

    /**
     * @var $boot
     */
    public $boot=false;

    /**
     * Application constructor.
     * @param $environment
     * @param bool $console
     */
    public function __construct($environment, $console=false){

        //get environment for app
        //get console status for cli
        $this->environment=$environment;
        $this->console=$console;

        //this method only includes the settings to be run in the development environment
        //the managed classes can be managed from the kernel file
        $this->devEagerConfiguration();

        //start boot for app
        //this method is the main fire and is brain for system
        $this->booting();

        //middleware installers are user-based pre-installers
        //that come after the pre-installers required for the system
        $this->middleware();
    }

    public function devEagerConfiguration(){

        //kernel eager for dev
        $this->devEagers($this);
    }

    /**
     * @method console
     * @return bool|null
     */
    public function console(){

        return $this->console;
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
}