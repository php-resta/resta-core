<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;
use Resta\Middleware\MiddlewareKernelProvider;
use Resta\Foundation\ApplicationBaseRegister as BaseRegister;

class Bootstrappers
{
    /**
     * @var array $stack
     */
    protected $stack = [];

    /**
     * @var ApplicationContracts $app
     */
    protected $app;

    /**
     * load bootstrappers for kernel.
     *
     * @var string
     */
    protected const LOADBOOTSTRAPPERS = 'loadBootstrappers';

    /**
     * Bootstrappers constructor.
     *
     * @param ApplicationContracts|ContainerContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        // the concrete object is the callback class itself that is sent to this class.
        // once the concrete object has been assigned, we run the bootstrappers sequence
        $this->app = $app;

        //we boot the initial instance for the application.
        (new BaseRegister($this->app))->handle();

        //we save the bootstrapper class in container.
        $this->app->register('bootstrapper',$this);

        //call bootstrapper process
        $this->callBootstrapperProcess();

        //we are peeling.
        $this->peelings();
    }

    /**
     * kernel manifest boot
     *
     * @param $bootstrapper
     */
    private function boot($bootstrapper)
    {
        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        BootFireCallback::setBootFire([$this->app,$bootstrapper],function($boot){

            //kernel boots run and service container
            //makeBuild for service Container
            $this->bootFire($boot,null);
        });
    }

    /**
     * kernel boot manager method
     *
     * @param array $boot
     * @param string $maker
     * @return mixed
     */
    public function bootFire($boot,$maker)
    {
        // we can refer to this method
        // because we can boot classes in the middleware or bootstrapper array.
        if(!is_array($boot) && !is_null($maker)){

            /** @var KernelManifestManager $kernelManifestBind */
            $kernelManifestBind = $this->app->resolve(KernelManifestManager::class);

            // we create kernel bootstrapping objects
            // that can be changed by you with the closure dispatcher method.
            return $kernelManifestBind->handle($maker);
        }

        // the boot method to be executed can be specified by the user.
        // we use this method to know how to customize it.
        return forward_static_call_array([array_pop($boot),self::LOADBOOTSTRAPPERS],[$boot]);
    }

    /**
     * application bootstrapper process
     *
     * @param mixed ...$params
     * @return bool
     */
    private function bootstrapper(...$params)
    {
        [$group,$booting,$onion] = $params;

        if($onion){

            // we will implement a special onion method here and
            // pass our bootstraper classes through this method.
            // Our goal here is to implement the middleware layer correctly.
            $this->app->resolve(MiddlewareKernelProvider::class)->onionBoot([$group,$booting],function() use($group){
                $this->boot($group);
            });

            return false;
        }

        //system booting for app
        //pre-loaders are the most necessary classes for the system.
        $this->boot($group);
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
            call_user_func_array([$this,'bootstrapper'],[$bootstrapper,$this,$this->stack['onionIdentifier']]);
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

            $this->app->resolved(MiddlewareKernelProvider::class)->onionRun($peelings);
        }
    }
}