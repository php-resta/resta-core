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
    private function applyProvider($key,$provider,$method='register')
    {
        // If the provider classes are a real object
        // we will run them.
        if(Utils::isNamespaceExists($provider)){

            // after determining whether the register or boot methods
            // we are running the provider.
            $providerInstance = $this->app->resolve($provider);

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
     * assign loadedProviders core value
     *
     * @return mixed|void
     */
    private function assignerLoadedProvidersInitialCoreValue()
    {
        if(!isset($this->app['loadedProviders'])){

            // for loaded providers,
            // we register an empty array for the container object.
            $this->app->register('loadedProviders',[]);
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
            $providers = $this->app->serviceProviders();

            if(count($providers)){
                $this->providers = $providers;
            }
        }

        return $this->providers ?: [];
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
     * resolve providers
     *
     * @param array $providers
     */
    public function resolveProviders($providers=array())
    {
        // for loaded providers,
        // we register an empty array for the container object.
        $this->assignerLoadedProvidersInitialCoreValue();

        //first we are running register methods of provider classes.
        foreach($providers as $key=>$provider){

            // providers can only be installed once.
            // apply providers and register for kernel
            if(!isset($this->app['loadedProviders'][$key])){
                $this->applyProvider($key,$provider);
            }
        }

        //then we are running boot methods of provider classes.
        foreach($providers as $key=>$provider){

            //if the providers register is already booted.
            if(isset($this->app['loadedProviders'][$key])){
                $this->applyProvider($key,$provider,'boot');
            }
        }
    }
}