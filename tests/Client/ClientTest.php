<?php

namespace Resta\Core\Tests\Client;

use OverflowException;
use Resta\Core\Tests\Client\Data\User2\User2;
use UnexpectedValueException;
use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Client\Data\User\User;
use ReflectionException as ReflectionExceptionAlias;

class ClientTest extends AbstractTest
{
    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient()
    {
        $this->expectException(UnexpectedValueException::class);
        $user = new User(['a'=>'b']);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient2()
    {
        $user = new User(['a'=>1,'password'=>123456]);
        $this->assertArrayHasKey('password',$user->all());
        $this->assertArrayHasKey('a',$user->all());
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient3()
    {
        $user = new User(['a'=>1,'b'=>1,'password'=>123456]);
        $this->assertArrayHasKey('password',$user->all());
        $this->assertArrayHasKey('a',$user->all());
        $this->assertArrayHasKey('b',$user->all());
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient4()
    {
        $this->expectException(OverflowException::class);
        new User(['a'=>1,'b'=>1,'c'=>1,'password'=>123456]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient5()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'password'=>123456]);
        $this->assertArrayNotHasKey('a',$user2->all());
        $this->assertArrayHasKey('b',$user2->all());
        $this->assertArrayHasKey('password',$user2->all());

    }

}