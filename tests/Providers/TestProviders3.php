<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders3 extends ServiceProviderManager implements BootContracts
{
    /**
     * load dependencies for provider
     *
     * @var array $dependencies
     */
    protected $dependencies = [];

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
        //
    }
}