<?php

namespace Resta\Core\Tests\Providers;

use Resta\Contracts\BootContracts;
use Resta\Provider\ServiceProviderManager;

class TestProviders1 extends ServiceProviderManager implements BootContracts
{
    /**
     * load dependencies for provider
     *
     * @var array $dependencies
     */
    protected $dependencies = ['test2'];

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