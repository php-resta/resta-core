<?php

namespace Resta\Core\Tests\Container;

use Resta\Foundation\Application;
use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Container\Dummy\ResolveDummy;

class ContainerTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();
    }

    public function testInstanceResolve()
    {
        $this->assertSame(1,(new ResolveDummy(app()))->getCounter());
        $this->assertSame(2,(new ResolveDummy(app()))->getCounter());

        $this->assertInstanceOf(ResolveDummy::class,static::$app->resolve(ResolveDummy::class));
        $this->assertInstanceOf(Application::class,static::$app->resolve(ResolveDummy::class)->app());
        $this->assertSame(null,static::$app->resolve(ResolveDummy::class)->getDummy());
        $this->assertSame(3,static::$app->resolve(ResolveDummy::class)->getCounter());
        $this->assertSame(3,static::$app->resolve(ResolveDummy::class)->getCounter());
        $this->assertSame(1,app()->resolve(ResolveDummy::class,['dummy'=>1])->getDummy());
        $this->assertSame(4,app()->resolve(ResolveDummy::class,['dummy'=>1])->getCounter());

        $this->assertInstanceOf(ResolveDummy::class,app()->resolve(ResolveDummy::class));
        $this->assertSame(5,app()->resolve(ResolveDummy::class)->getCounter());
        $this->assertInstanceOf(Application::class,app()->resolve(ResolveDummy::class)->app());
        $this->assertSame(null,app()->resolve(ResolveDummy::class)->getDummy());
        $this->assertSame(5,app()->resolve(ResolveDummy::class)->getCounter());
        $this->assertSame(1,app()->resolve(ResolveDummy::class,['dummy'=>1])->getDummy());
        $this->assertSame(6,app()->resolve(ResolveDummy::class,['dummy'=>1])->getCounter());
    }


    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testShareContainer()
    {
        $this->assertTrue(true,static::$app->console());

        static::$app->share("consoleShareControl",function()
        {
           return ResolveDummy::class;
        });


        $this->assertTrue(true,static::$app['consoleShared']['consoleShareControl']);
        $this->assertSame("Resta\Core\Tests\Container\Dummy\ResolveDummy",static::$app['consoleShared']['consoleShareControl']);
        $this->assertSame("Resta\Core\Tests\Container\Dummy\ResolveDummy",get_class(static::$app['consoleShareControl']));
        $this->assertInstanceOf(ResolveDummy::class,static::$app['consoleShareControl']);
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testMakeContainer()
    {
        static::$app->make("consoleShareControl2",function()
        {
            return ResolveDummy::class;
        });

        $this->assertFalse(false,isset(static::$app['consoleShared']['consoleShareControl2']));
        $this->assertFalse(false,isset(static::$app['bindings']['consoleShareControl2']));

    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testContainerGetHas()
    {
        static::$app->register('a','b');
        $this->assertTrue(true,static::$app->has('a'));
        $this->assertSame('b',static::$app->get('a'));
    }

    /**
     * @return void|mixed
     */
    public function testContainerGetHasArray()
    {
        static::$app->register('a1','0','c1');
        static::$app->register('a1','1','c2');
        $this->assertSame(['c1','c2'],static::$app->get('a1'));

        $this->assertTrue(true,static::$app->has('a1.0'));
        $this->assertTrue(true,static::$app->has('a1.1'));
        $this->assertFalse(false,static::$app->has('a1.2'));

        $this->assertSame('c1',static::$app->get('a1.0'));
        $this->assertSame('c2',static::$app->get('a1.1'));
        $this->assertSame(null,static::$app->get('a1.2'));

        static::$app->register('b1',0,'c1');
        static::$app->register('b1',1,'c2');
        $this->assertSame(['c1','c2'],static::$app->get('b1'));

        $this->assertTrue(true,static::$app->has('b1.0'));
        $this->assertTrue(true,static::$app->has('b1.1'));
        $this->assertFalse(false,static::$app->has('b1.2'));

        $this->assertSame('c1',static::$app->get('b1.0'));
        $this->assertSame('c2',static::$app->get('b1.1'));
        $this->assertSame(null,static::$app->get('b1.2'));
    }

    public function testContainerTerminate()
    {
        static::$app->terminate('a1','0');

        $this->assertSame(null,static::$app->get('a1.0'));
        $this->assertFalse(false,isset(static::$app['a1']['0']));
        $this->assertSame(null,app()->get('a1.0'));
        $this->assertSame('c2',static::$app->get('a1.1'));
        $this->assertSame('c2',static::$app['a1']['1']);
        $this->assertSame('c2',app()->get('a1.1'));

        static::$app->terminate('a1','1');

        $this->assertSame(null,static::$app->get('a1.1'));
        $this->assertFalse(false,isset(static::$app['a1']['1']));
        $this->assertSame(null,app()->get('a1.1'));
        $this->assertSame([],static::$app->get('a1'));
        $this->assertSame([],app()->get('a1'));

        static::$app->terminate('a1');
        $this->assertSame(null,static::$app->get('a1'));
        $this->assertFalse(false,isset(static::$app['a1']));


        static::$app->terminate('b1','0');

        $this->assertSame(null,static::$app->get('b1.0'));
        $this->assertFalse(false,isset(static::$app['b1']['0']));
        $this->assertSame(null,app()->get('b1.0'));
        $this->assertSame('c2',static::$app->get('b1.1'));
        $this->assertSame('c2',static::$app['b1']['1']);
        $this->assertSame('c2',app()->get('b1.1'));

        static::$app->terminate('b1','1');

        $this->assertSame(null,static::$app->get('b1.1'));
        $this->assertFalse(false,isset(static::$app['b1']['1']));
        $this->assertSame(null,app()->get('b1.1'));
        $this->assertSame([],static::$app->get('b1'));
        $this->assertSame([],app()->get('b1'));

        static::$app->terminate('b1');
        $this->assertSame(null,static::$app->get('b1'));
        $this->assertFalse(false,isset(static::$app['b1']));

    }

    /**
     * @return void|mixed
     */
    public function testContainerGetHasArrayWithAppInstance()
    {
        $this->assertSame(null,app()->get('a1'));
        $this->assertSame(null,app()->get('b1'));

        app()->register('a1','0','c1');
        app()->register('a1','1','c2');
        $this->assertSame(['c1','c2'],app()->get('a1'));

        $this->assertTrue(true,app()->has('a1.0'));
        $this->assertTrue(true,app()->has('a1.1'));
        $this->assertFalse(false,app()->has('a1.2'));

        $this->assertSame('c1',app()->get('a1.0'));
        $this->assertSame('c2',app()->get('a1.1'));
        $this->assertSame(null,app()->get('a1.2'));

        app()->register('b1',0,'c1');
        app()->register('b1',1,'c2');
        $this->assertSame(['c1','c2'],app()->get('b1'));

        $this->assertTrue(true,app()->has('b1.0'));
        $this->assertTrue(true,app()->has('b1.1'));
        $this->assertFalse(false,app()->has('b1.2'));

        $this->assertSame('c1',app()->get('b1.0'));
        $this->assertSame('c2',app()->get('b1.1'));
        $this->assertSame(null,app()->get('b1.2'));



        static::$app->register('a1','0','c1');
        static::$app->register('a1','1','c2');
        $this->assertSame(['c1','c2'],app()->get('a1'));

        $this->assertTrue(true,app()->has('a1.0'));
        $this->assertTrue(true,app()->has('a1.1'));
        $this->assertFalse(false,app()->has('a1.2'));

        $this->assertSame('c1',app()->get('a1.0'));
        $this->assertSame('c2',app()->get('a1.1'));
        $this->assertSame(null,app()->get('a1.2'));

        static::$app->register('b1',0,'c1');
        static::$app->register('b1',1,'c2');
        $this->assertSame(['c1','c2'],app()->get('b1'));

        $this->assertTrue(true,app()->has('b1.0'));
        $this->assertTrue(true,app()->has('b1.1'));
        $this->assertFalse(false,app()->has('b1.2'));

        $this->assertSame('c1',app()->get('b1.0'));
        $this->assertSame('c2',app()->get('b1.1'));
        $this->assertSame(null,app()->get('b1.2'));
    }
}