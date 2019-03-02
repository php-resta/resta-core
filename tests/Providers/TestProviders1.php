<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders1 extends ServiceProviderManager implements BootContracts
{
    /**
     * boot service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register('testprovider1boot',true);
    }

    /**
     * register service provider
     *
     * @return void
     */
    public function register()
    {
       $this->app->register('testprovider1register',true);
    }
}