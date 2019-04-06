<?php

namespace Resta\Core\Tests\Container;

use Resta\Core\Tests\AbstractTest;
use Resta\Container\DIContainerManager;
use Resta\Core\Tests\Container\Dummy\ContainerBindClass;
use Resta\Core\Tests\Container\Dummy\ContainerBindCallClass;
use Resta\Core\Tests\Container\Dummy\ContainerBindInterface;

class ContainerBindTest extends AbstractTest
{
    /**
     * @return mixed|void
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function setUp()
    {
        parent::setUp();

        static::$app->bind(ContainerBindInterface::class,function()
        {
            return ContainerBindClass::class;

        });
    }

    /**
     * @return void
     */
    public function testContainerBind()
    {
        $this->assertArrayHasKey('Resta\Core\Tests\Container\Dummy\ContainerBindInterface',static::$app['serviceContainer']);
        $this->assertSame("Resta\Core\Tests\Container\Dummy\ContainerBindClass",static::$app['serviceContainer']['Resta\Core\Tests\Container\Dummy\ContainerBindInterface']);
    }

    /**
     * @return void
     */
    public function testContainerBindCall()
    {
        $this->assertTrue(true,DIContainerManager::callBind([ContainerBindCallClass::class,'get'],static::$app->applicationProviderBinding(static::$app)));
    }
}