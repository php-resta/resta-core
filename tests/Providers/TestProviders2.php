<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders2 extends ServiceProviderManager implements BootContracts
{
    /**
     * load dependencies for provider
     *
     * @var array $dependencies
     */
    protected $dependencies = ['test3'];

    /**
     * boot service provider
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * register service provider
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('provider2',true);
    }
}