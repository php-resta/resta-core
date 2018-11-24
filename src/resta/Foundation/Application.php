<?php

namespace Resta\Foundation;

use Resta\ClosureDispatcher;
use Resta\Traits\ApplicationPath;
use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\ApplicationHelpersContracts;

class Application extends Kernel implements ApplicationContracts,ApplicationHelpersContracts
{
    //get app paths
    use ApplicationPath;

    /**
     * @var $console null
     */
    protected $console;

    /**
     * Application constructor.
     * @param bool $console
     */
    public function __construct($console=false)
    {
        //get console status for cli
        $this->console=$console;

        //The bootstrapper method is the initial process
        //that runs the individual methods that the application initiated.
        new Bootstrappers($this);
    }

    /**
     * @param null $boot
     * @param null $maker
     * @return mixed
     */
    public function bootFire($boot=null,$maker=null)
    {
        //we can refer to this method
        //because we can boot classes in the middleware or bootstrapper array.
        if($boot===null && $maker!==null){

            //We create kernel bootstrapping objects
            //that can be changed by you with the closure dispatcher method.
            return ClosureDispatcher::bind(KernelBootManager::class)
                ->call(function() use ($maker){
                    return $this->handle($maker);
            });
        }

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        return forward_static_call_array([array_pop($boot),'loadBootstrappers'],[$boot]);
    }

    /**
     * @return bool|mixed|null
     */
    public function console()
    {
        //Controlling the console object is
        //intended to make sure that the kernel bootstrap classes do not work.
        return $this->console;
    }

    /**
     * @return null
     */
    public function handle()
    {
        //This is the main calling place of your application.
        //If you come via http, the kernel response value is appraised.
        //If you come via console, the kernel console value is appraised.
        return ($this->console()) ? null : $this->kernel->response;
    }
}