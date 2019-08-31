<?php

namespace Resta\Provider;

use Resta\Support\Utils;
use Resta\Support\JsonHandler;
use Resta\Support\SuperClosure;
use Resta\Foundation\ApplicationProvider;
use Resta\Exception\FileNotFoundException;

class ServiceProvider extends  ApplicationProvider
{
    /**
     * all service providers
     *
     * @var array
     */
    protected $providers = [];

    /**
     * apply provider class
     *
     * @param $key
     * @param $provider
     * @param string $method
     *
     * @throws FileNotFoundException
     */
    private function applyProvider($key,$provider,$method='register')
    {
        // If the provider classes are a real object
        // we will run them.
        if(Utils::isNamespaceExists($provider)){

            // after determining whether the register or boot methods
            // we are running the provider.
            /** @scrutinizer ignore-call */
            $providerInstance = $this->app->resolve($provider);

            //we need to do method check for provider.
            if(method_exists($providerInstance,$method)){
                $providerInstance->{$method}();

                if($method=="register"){
                    /** @scrutinizer ignore-call */
                    $this->app->register('loadedProviders',$key,$provider);
                    $this->deferrableProvider($providerInstance,$provider);
                }
            }
        }
    }

    /**
     * deferrable provider process
     *
     * @param $providerInstance
     * @param $provider
     *
     * @throws FileNotFoundException
     */
    private function deferrableProvider($providerInstance,$provider)
    {
        if($providerInstance instanceof DeferrableProvider && file_exists(serviceJson())){
            $deferrableProvides = $providerInstance->provides();

            foreach ($deferrableProvides as $deferrableProvide) {

                if($this->app->has($deferrableProvide)){

                    JsonHandler::$file = serviceJson();
                    $serviceJson = JsonHandler::get();

                    if(!isset($serviceJson['providers'][$provider])){

                        JsonHandler::set('providers-deferrable-classes',[
                            $provider => true
                        ]);

                        $container = $this->app->get($deferrableProvide);

                        if(!is_array($container)){
                            JsonHandler::set('container',[
                                $deferrableProvide => SuperClosure::set($container)
                            ]);
                        }
                        else{
                            foreach ($container as $containerKey=>$containerItem) {
                                JsonHandler::set('container',[
                                    $deferrableProvide.'.'.$containerKey => SuperClosure::set($containerItem)
                                ]);
                            }
                        }
                    }
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
            /** @scrutinizer ignore-call */
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
        /** @scrutinizer ignore-call */
        $providers = $this->app->serviceProviders();

        if(count($providers)){
            $this->providers = $providers;
        }

        return $this->providers;
    }

    /**
     * handle service providers
     *
     * @throws FileNotFoundException
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
     *
     * @throws FileNotFoundException
     */
    public function resolveProviders($providers=array())
    {
        // for loaded providers,
        // we register an empty array for the container object.
        $this->assignerLoadedProvidersInitialCoreValue();

        //first we are running register methods of provider classes.
        foreach($providers as $key=>$provider){

            JsonHandler::$file = serviceJson();
            $serviceJson = JsonHandler::get();

            // providers can only be installed once.
            // apply providers and register for kernel
            if(!isset($this->app['loadedProviders'][$key])){

                if(is_array($provider) && isset($provider['status']) && $provider['status']){
                    if(!isset($serviceJson['providers-deferrable-classes'][$provider['class']])){
                        $this->applyProvider($key,$provider['class']);
                    }

                }
                else{
                    $this->applyProvider($key,$provider);
                }

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