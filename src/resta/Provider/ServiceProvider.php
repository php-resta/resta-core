<?php

namespace Resta\Provider;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ServiceProvider extends  ApplicationProvider
{
    /**
     * all service providers
     *
     * @var $providers
     */
    protected $providers;

    /**
     * apply provider class
     *
     * @param $key
     * @param $provider
     * @param string $method
     */
    public function applyProvider($key,$provider,$method='register')
    {
        // If the provider classes are a real object
        // we will run them.
        if(Utils::isNamespaceExists($provider)){

            // after determining whether the register or boot methods
            // we are running the provider.
            $providerInstance = $this->app->makeBind($provider);

            // this is very important.
            // providers install dependencies before it.
            // we need to check the value of kernel dependencies in order to avoid infinite loops.
            if(!isset(core()->dependencies)){
                $this->resolveDependenciesForProviders($providerInstance);
            }

            //we need to do method check for provider.
            if(method_exists($providerInstance,$method)){
                $providerInstance->{$method}();

                if($method=="register"){
                    $this->app->register('loadedProviders',$key,$provider);
                }
            }
        }
    }

    /**
     * get all service providers
     *
     * @return array
     */
    public function getServiceProviders()
    {
        //set service providers for providers property
        if($this->providers===null){
            $this->providers = $this->app->serviceProviders();
        }

        return $this->providers;
    }

    /**
     * handle service providers
     *
     * @return void|mixed
     */
    public function handle()
    {
        define ('serviceprovider',true);

        //check providers and resolve
        $this->resolveProviders($this->getServiceProviders());
    }

    /**
     * resolve dependencies for providers
     *
     * @param $providerInstance
     */
    private function resolveDependenciesForProviders($providerInstance)
    {
        //get dependencies for providers
        $dependencies = $providerInstance->dependencies();

        foreach ($dependencies as $dependency){

            if(isset($this->providers[$dependency])){

                // we have to save the dependencies kernel value for dependencies.
                // then the dependencies are executed and the kernel value is terminated at the end.
                $this->app->register('dependencies',true);
                $this->resolveProviders([$dependency=>$this->providers[$dependency]]);
                $this->app->terminate('dependencies');
            }
        }
    }

    /**
     * resolve providers
     *
     * @param $providers
     */
    public function resolveProviders($providers)
    {
        //first we are running register methods of provider classes.
        foreach($providers as $key=>$provider){

            // providers can only be installed once.
            // apply providers and register for kernel
            if(!isset(core()->loadedProviders[$key])){
                $this->applyProvider($key,$provider);
            }
        }

        //then we are running boot methods of provider classes.
        foreach($providers as $key=>$provider){

            //if the providers register is already booted.
            if(isset(core()->loadedProviders[$key])){
                $this->applyProvider($key,$provider,'boot');
            }
        }
    }
}