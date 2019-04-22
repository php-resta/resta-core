<?php

namespace Resta\Core\Tests\Providers;

use Resta\Core\Tests\AbstractTest;
use Resta\Provider\ServiceProvider;

class ProviderTest extends AbstractTest
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();

        static::$app->loadConfig(function()
        {
            return ['kernel'=>[
                'providers'=>[
                    'test1' => TestProviders1::class,
                    'test2' => TestProviders2::class,
                    'test3' => TestProviders3::class,
                ]
            ]];
        });

        $providerInstance = static::$app->resolve(ServiceProvider::class);
        $providerInstance->getServiceProviders();
        $providerInstance->resolveProviders(static::$app->serviceProviders());
    }

    /**
     * @return void|mixed
     */
    public function testCountServiceProviders()
    {
        $this->assertSame(3,count(static::$app->serviceProviders()));
    }

    /**
     * @return void|mixed
     */
    public function testRunServiceProviders()
    {
        $this->assertSame(config('kernel.providers'),core()->loadedProviders);
    }

    /**
     * @return void|mixed
     */
    public function testServiceProvidersCheck()
    {
        $this->assertTrue(true,isset(core()->testprovider1register));
        $this->assertTrue(true,isset(core()->testprovider1boot));

        $this->assertTrue(true,isset(core()->testprovider2register));
        $this->assertTrue(true,isset(core()->testprovider2boot));

        $this->assertTrue(true,isset(core()->testprovider2register));
        $this->assertTrue(true,isset(core()->testprovider2boot));
    }
}