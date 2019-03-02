<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders2 extends ServiceProviderManager implements BootContracts
{
    /**
     * boot service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register('testprovider2boot',true);
    }

    /**
     * register service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('testprovider2register',true);
    }
}