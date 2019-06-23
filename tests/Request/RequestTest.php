<?php

namespace Resta\Core\Tests\Request;

use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Request\Data\User\UserRequest;
use ReflectionException as ReflectionExceptionAlias;

class ConfigTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * test request 1
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest1()
    {
        $this->expectException(\UnexpectedValueException::class);
        new UserRequest(['a'=>'b']);
    }

    /**
     * test request 2
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest2()
    {
        $this->expectException(\InvalidArgumentException::class);
        new UserRequest(['username'=>'b']);
    }

    /**
     * test request 3
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest3()
    {
        $request = new UserRequest(['username'=>'aligurbuz']);

        $this->assertSame(['username'=>'aligurbuz'],$request->all());
    }
}