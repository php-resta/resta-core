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
                    'test1' => ['class' => TestProviders1::class,'status'=>true],
                    'test2' => ['class' => TestProviders2::class,'status'=>true],
                    'test3' => ['class' => TestProviders3::class,'status'=>true],
                    'test4' => ['class' => TestProviders4::class,'status'=>true],
                ]
            ]];
        });

        $providerInstance = static::$app->resolve(ServiceProvider::class);
        $providerInstance->resolveProviders(static::$app->serviceProviders());
    }

    /**
     * @return void|mixed
     */
    public function testCountServiceProviders()
    {
        $this->assertSame(4,count(static::$app->serviceProviders()));
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

    /**
     * @return void|mixed
     */
    public function testServiceProvidersCacheContainer()
    {
        $this->assertTrue(true,static::$app->has('testProviderCache1'));
        $this->assertSame('test1',static::$app->get('testProviderCache1'));
        $this->assertTrue(true,static::$app->has('testProviderCache2'));
        $this->assertSame('test2',static::$app->get('testProviderCache2'));

        $this->assertArrayHasKey('a',static::$app->get('testProviderCache3'));
        $this->assertArrayHasKey('b',static::$app->get('testProviderCache3'));
        $this->assertArrayHasKey('c',static::$app->get('testProviderCache3'));

        $this->assertTrue(true,static::$app->get('testProviderCache3.a'));
        $this->assertFalse(false,static::$app->get('testProviderCache3.b'));

        $this->assertSame(1,static::$app->get('testProviderCache3.c')(1));
        $this->assertSame('1',static::$app->get('testProviderCache3.c')('1'));

    }
}