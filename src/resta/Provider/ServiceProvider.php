<?php

namespace Resta\Provider;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;

class ServiceProvider extends  ApplicationProvider
{
    /**
     * handle service providers
     *
     * @return void|mixed
     */
    public function handle()
    {
        //get kernel service providers for application
        $providers = config('kernel.providers');

        if(is_array($providers)){

            //first we are running register methods of provider classes.
            foreach($providers as $key=>$provider){
                $this->applyProvider($provider);
                $this->app->register('loadedProviders',$key,$provider);
            }

            //then we are running boot methods of provider classes.
            foreach($providers as $key=>$provider){
                $this->applyProvider($provider,'boot');
            }
        }
    }

    /**
     * apply provider class
     *
     * @param $provider
     * @param string $method
     */
    public function applyProvider($provider,$method='register')
    {
        // If the provider classes are a real object
        // we will run them.
        if(Utils::isNamespaceExists($provider)){

            // after determining whether the register or boot methods
            // we are running the provider.
            $providerInstance = $this->app->makeBind($provider);
            if(method_exists($providerInstance,$method)){
                $providerInstance->{$method}();
            }
        }
    }
}