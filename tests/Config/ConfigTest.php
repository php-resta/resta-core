<?php

namespace Resta\Core\Tests\Config;

use Resta\Config\Config;
use Resta\Core\Tests\AbstractTest;

class ConfigTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();

        static::$app->loadConfig(function()
        {
            return ['core'=>[
                'test1'=>'foo',
                'test2'=>[
                    'nested1'=>'nested1value'
                ]
                ]
            ];
        });
    }

    /**
     * @return void|mixed
     */
    public function testConfigHelper()
    {
        $this->assertSame('foo',config('core.test1'));
        $this->assertSame('nested1value',config('core.test2.nested1'));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testConfigFacade()
    {
        $this->assertInstanceOf(Config::class,Config::make());

        $this->assertSame('foo',Config::make('core.test1')->get());
        $this->assertSame('nested1value',Config::make('core.test2.nested1')->get());
    }

}