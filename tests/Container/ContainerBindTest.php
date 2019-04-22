<?php

namespace Resta\Core\Tests\Container;

use OverflowException;
use DI\NotFoundException;
use DI\DependencyException;
use Resta\Core\Tests\AbstractTest;
use Resta\Container\DIContainerManager;
use Resta\Contracts\{ApplicationContracts};
use Resta\Core\Tests\Container\Dummy\ContainerBindClass;
use Resta\Core\Tests\Container\Dummy\ContainerBindCallClass;
use Resta\Core\Tests\Container\Dummy\ContainerBindInterface;

class ContainerBindTest extends AbstractTest
{
    /**
     * @return mixed|void
     *
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function setUp()
    {
        parent::setUp();

        if(!isset(static::$app['serviceContainer']['Resta\Core\Tests\Container\Dummy\ContainerBindInterface'])){

            static::$app->bind(ContainerBindInterface::class,function()
            {
                return ContainerBindClass::class;

            });
        }
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

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testCallbackReturnBind()
    {
        static::$app->bind("bindApp",function(ApplicationContracts $app)
        {
            return $app;

        });

        static::$app->bind("bindApp2",function()
        {
            return 'bindApp2';

        });


        $this->assertTrue(true,isset(static::$app['serviceContainer']['bindApp']));
        $this->assertInstanceOf(ApplicationContracts::class,static::$app['serviceContainer']['bindApp']);
        $this->assertInstanceOf(ApplicationContracts::class,bind()->bindApp);

        $this->assertTrue(true,isset(static::$app['serviceContainer']['bindApp2']));
        $this->assertSame("bindApp2",static::$app['serviceContainer']['bindApp2']);
        $this->assertSame("bindApp2",bind()->bindApp2);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testContainerBindMultipleEntity()
    {
        $this->expectException(OverflowException::class);

        static::$app->bind("multiple",function(ApplicationContracts $app)
        {
            return $app;

        });

        static::$app->bind("multiple",function()
        {
            return 'bindApp2';

        });
    }
}