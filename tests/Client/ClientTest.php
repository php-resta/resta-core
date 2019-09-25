<?php

namespace Resta\Core\Tests\Client;

use OverflowException;
use UnexpectedValueException;
use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Client\Data\User\User;
use Resta\Core\Tests\Client\Data\User2\User2;
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

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient6()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'password'=>123456]);
        $this->assertArrayNotHasKey('a',$user2->all());
        $this->assertArrayHasKey('b',$user2->all());
        $this->assertArrayHasKey('password',$user2->all());

        $this->assertSame(1,$user2->input('b'));
        $this->assertSame(md5(123456),$user2->input('password'));
        $this->assertNotSame(123456,$user2->input('password'));

        $this->assertFalse(false,$user2->has('a'));
        $this->assertTrue(true,$user2->has('b'));
        $this->assertTrue(true,$user2->has('password'));

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient7()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'test'=>2,'test2'=>'death','test3'=>1,'password'=>123456]);

        $this->assertNotSame(2,$user2->input('test'));
        $this->assertSame(1,$user2->input('test'));

        $this->assertNotSame('test2',$user2->input('test2'));
        $this->assertSame('death',$user2->input('test2'));

        $this->assertNotSame(1,$user2->input('test3'));
        $this->assertSame('test3',$user2->input('test3'));

    }

}