<?php

namespace Resta\Core\Tests\Providers;

use Resta\Provider\DeferrableProvider;
use Resta\Provider\ServiceProviderManager;

class TestProviders4 extends ServiceProviderManager implements DeferrableProvider
{
    /**
     * register service provider
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->register('testProviderCache1','test1');
        $this->app->register('testProviderCache2','test2');

        $this->app->register('testProviderCache3','a',true);
        $this->app->register('testProviderCache3','b',false);
        $this->app->register('testProviderCache3','c',function($value=1){
            return $value;
        });

    }

    /**
     * @return array
     */
    public function provides() : array
    {
        return [
            'testProviderCache1',
            'testProviderCache2',
            'testProviderCache3',
        ];
    }
}