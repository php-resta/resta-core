<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Contracts\ApplicationContracts;
use Resta\Contracts\ContainerContracts;
use Resta\Foundation\ApplicationBaseRegister as BaseRegister;

class Bootstrappers
{
    /**
     * @var array $stack
     */
    protected $stack = [];

    /**
     * @var $app ApplicationContracts|ContainerContracts
     */
    protected $app;

    /**
     * Bootstrappers constructor.
     *
     * @param ApplicationContracts|ContainerContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        //The concrete object is the callback class itself that is sent to this class.
        //Once the concrete object has been assigned, we run the bootstrappers sequence
        $this->app          = $app;

        //we boot the initial instance for the application.
        (new BaseRegister($this->app))->handle();

        //call bootstrapper process
        $this->callBootstrapperProcess();

        // we are peeling.
        $this->peelings();
    }

    /**
     * call bootstrappers for application
     *
     * @param array $customBootstrapers
     */
    public function callBootstrapperProcess($customBootstrapers=[])
    {
        // here we check that a special bootstrappers list will work and we identify the onion identifier.
        // we are peeling onion class by classifying onion class.
        $this->getBootstrappersStack($customBootstrapers);

        //We run the bootstrap list by callback with the object specified for the content respectively.
        foreach($this->stack['getBootstrappers'] as $bootstrapper){

            // if the callback data is different from the application kernel,
            // we will pass it to the pusher control for a special use.
            call_user_func_array([$this->app,__FUNCTION__],[$bootstrapper,$this,$this->stack['onionIdentifier']]);
        }
    }

    /**
     * get bootstrapper stack
     *
     * @param $customBootstrapers
     */
    private function getBootstrappersStack($customBootstrapers)
    {
        // here we check that a special bootstrappers list will work and we identify the onion identifier.
        // we are peeling onion class by classifying onion class.
        $customBootstrapersCount            = count($customBootstrapers);
        $getBootstrappers                   = $this->app->kernelGroupKeys();
        $this->stack['getBootstrappers']    = ($customBootstrapersCount) ? $customBootstrapers : $getBootstrappers;
        $this->stack['onionIdentifier']     = ($customBootstrapersCount) ? false : true;
    }

    /**
     * @method peelings
     * @return mixed|void
     */
    private function peelings()
    {
        //if there are peelings
        if(isset($this->app['peelings'])){

            // We send the peelings property to
            // the bootstrapperPeelOnion class.
            $peelings = $this->app['peelings'];
            pos($peelings)->onionRun($peelings);
        }
    }
}