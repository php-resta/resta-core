<?php

namespace Resta\Foundation;

use Resta\ClosureDispatcher;
use Resta\Traits\ApplicationPath;
use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\ApplicationHelpersContracts;

class Application extends Kernel implements ApplicationContracts,ApplicationHelpersContracts {

    //get app paths
    use ApplicationPath;

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
     * @param null $boot
     * @param null $maker
     * @return mixed
     */
    public function bootFire($boot=null,$maker=null){

        //we can refer to this method
        //because we can boot classes in the middleware or bootstrappers array.
        if($boot===null && $maker!==null){

            //We create kernel bootstrapping objects
            //that can be changed by you with the closure dispatcher method.
            return $this->makeBind(ClosureDispatcher::class,['bind'=>new KernelBootManager()])->call(function() use ($maker){
                return $this->handle($maker);
            });
        }

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        return forward_static_call_array([array_pop($boot),'loadBootstrappers'],[$boot]);
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