<?php

namespace Resta\Core\Tests\Container;

use Resta\Core\Tests\AbstractTest;
use Resta\Foundation\Application;
use Resta\Core\Tests\Container\Dummy\ResolveDummy;

class ApplicationTest extends AbstractTest
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
        $this->assertInstanceOf(ResolveDummy::class,static::$app->resolve(ResolveDummy::class));
        $this->assertInstanceOf(Application::class,static::$app->resolve(ResolveDummy::class)->app);
        $this->assertSame(null,static::$app->resolve(ResolveDummy::class)->getDummy());
        $this->assertSame(1,static::$app->resolve(ResolveDummy::class,['dummy'=>1])->getDummy());
    }
}