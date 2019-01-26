<?php

namespace Resta\Foundation;

use Resta\Support\Utils;
use Resta\ClosureDispatcher;
use Resta\Traits\ApplicationPath;
use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\ApplicationHelpersContracts;
use Resta\Foundation\Bootstrapper\Bootstrappers;
use Resta\Foundation\Bootstrapper\KernelBootManager;

class Application extends Kernel implements ApplicationContracts,ApplicationHelpersContracts
{
    //get app paths
    use ApplicationPath;

    /**
     * @var bool $console
     */
    protected $console;

    /**
     * Application constructor.
     *
     * @param bool $console
     */
    public function __construct($console=false)
    {
        // get console status for cli
        $this->console=$console;

        // the bootstrapper method is the initial process
        // that runs the individual methods that the application initiated.
        new Bootstrappers($this);
    }

    /**
     * kernel boot manager method
     *
     * @param null $boot
     * @param null $maker
     * @return mixed
     */
    protected function bootFire($boot=null,$maker=null)
    {
        // we can refer to this method
        // because we can boot classes in the middleware or bootstrapper array.
        if($boot===null && $maker!==null){

            // we create kernel bootstrapping objects
            // that can be changed by you with the closure dispatcher method.
            return ClosureDispatcher::bind(KernelBootManager::class)
                ->call(function() use ($maker){
                    return $this->handle($maker);
            });
        }

        // the boot method to be executed can be specified by the user.
        // we use this method to know how to customize it.
        return forward_static_call_array([array_pop($boot),'loadBootstrappers'],[$boot]);
    }

    /**
     * console kernel object
     *
     * @return bool|mixed|null
     */
    public function console()
    {
        // controlling the console object is
        // intended to make sure that the kernel bootstrap classes do not work.
        return $this->console;
    }

    /**
     * handle application
     *
     * @return null
     */
    public function handle()
    {
        // this is the main calling place of your application.
        // if you come via http, the kernel response value is appraised.
        // if you come via console, the kernel console value is appraised.
        return ($this->console()) ? null : $this->kernel->response;
    }

    /**
     *ContainerContracts configuration loader
     *
     * @param $assignPath false
     * @param callable $callback
     * @return mixed
     */
    public function loadConfig(callable $callback,$assignPath=false)
    {
        // it adds the values in path data specified
        // by callback to the configuration values.
        if(is_object($this['config'])){

            //set your path for config loader
            return tap($this['config'],function($config) use($callback) {
                return $config->setConfig(call_user_func($callback));
            });

        }
    }

    /**
     * @param null $name
     * @param null $path
     * @return mixed|void
     */
    public function setPaths($name=null,$path=null)
    {
        // save the globally identified paths to
        // the global container object of the resta.
        if($name!==null && $path!==null){
            $this->register('paths',$name,$path);
        }
    }
}