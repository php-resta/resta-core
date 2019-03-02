<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders3 extends ServiceProviderManager implements BootContracts
{
    /**
     * boot service provider
     *
     * @return void
     */
    public function boot()
    {
        if(!isset(core()->testprovider3)){
            exception()->runtime('register method is not applied');
        }
    }

    /**
     * register service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('testprovider3',true);
    }
}