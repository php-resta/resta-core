<?php

namespace Resta\Core\Tests\Client;

use OverflowException;
use InvalidArgumentException;
use UnexpectedValueException;
use Resta\Core\Tests\AbstractTest;
use Resta\Core\Tests\Client\Data\User\User;
use Resta\Core\Tests\Client\Data\User5\User5;
use Resta\Core\Tests\Client\Data\User3\User3;
use Resta\Core\Tests\Client\Data\User4\User4;
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
        new User(['a'=>'b']);
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
        $user = new User(['a'=>1,'b'=>1,'password'=>123456,'cgenerator1'=>'x','cgenerator2'=>'x','cgenerator3'=>'x']);
        $this->assertArrayHasKey('password',$user->all());
        $this->assertArrayHasKey('a',$user->all());
        $this->assertArrayHasKey('b',$user->all());
        $this->assertArrayHasKey('cgenerator1',$user->all());

        $this->assertSame('cgenerator1',$user->input('cgenerator1'));
        $this->assertNotSame('x',$user->input('cgenerator1'));

        $this->assertSame('x',$user->input('cgenerator2'));
        $this->assertNotSame('cgenerator2',$user->input('cgenerator2'));

        $this->assertSame('cgenerator3',$user->input('cgenerator3'));
        $this->assertNotSame('x',$user->input('cgenerator3'));
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
        $this->assertArrayHasKey('cgenerator1',$user2->all());

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient6()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'password'=>123456,'cgenerator1'=>'x','cgenerator2'=>'x','cgenerator3'=>'x']);
        $this->assertArrayNotHasKey('a',$user2->all());
        $this->assertArrayHasKey('b',$user2->all());
        $this->assertArrayHasKey('password',$user2->all());
        $this->assertArrayHasKey('cgenerator1',$user2->all());

        $this->assertSame('cgenerator1',$user2->input('cgenerator1'));
        $this->assertNotSame('x',$user2->input('cgenerator1'));

        $this->assertSame('x',$user2->input('cgenerator2'));
        $this->assertNotSame('cgenerator2',$user2->input('cgenerator2'));

        $this->assertSame('cgenerator3',$user2->input('cgenerator3'));
        $this->assertNotSame('x',$user2->input('cgenerator3'));

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

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient8()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'capsule1'=>'capsule1','capsule2'=>'capsule2','password'=>123456]);

        $this->assertArrayHasKey('capsule1',$user2->all());
        $this->assertArrayHasKey('capsule2',$user2->all());
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient9()
    {
        $this->expectException(OverflowException::class);
        new User2(['a'=>1,'b'=>1,'capsule1'=>'capsule1','capsule2'=>'capsule2','capsule3'=>'capsule3','password'=>123456]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient10()
    {
        $user2 = new User2(['a'=>1,'b'=>1,'capsule1'=>'capsule1','capsule2'=>'capsule2','password'=>123456,'rule1'=>123456]);
        $this->assertSame(123456,$user2->input('rule1'));
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient11()
    {
        $this->expectException(InvalidArgumentException::class);
        new User2(['a'=>1,'b'=>1,'capsule1'=>'capsule1','capsule2'=>'capsule2','password'=>123456,'rule1'=>'a1']);

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient12()
    {
        $this->expectException(InvalidArgumentException::class);
        new User2(['a'=>1,'b'=>1,'capsule1'=>'capsule1','capsule2'=>'capsule2','password'=>123456,'rule1'=>123]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient13()
    {
        $user3 = new User3(['username'=>'username']);
        $this->assertArrayHasKey('username',$user3->all());
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient14()
    {
        $user3 = new User3(['email'=>'test']);
        $this->assertArrayHasKey('email',$user3->all());


    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient15()
    {
        $this->expectException(UnexpectedValueException::class);
        new User3([]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient16()
    {
        $this->expectException(InvalidArgumentException::class);
        new User4(['status'=>'string']);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient17()
    {
        $this->expectException(InvalidArgumentException::class);
        new User4(['items'=>['status'=>'string','foo'=>'xxx']]);

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient18()
    {
        $this->expectException(OverflowException::class);
        new User4(['items'=>['status'=>'string','foox'=>'xxx']]);

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient19()
    {
        $user4 = new User4(['items'=>['status'=>1,'foo'=>'xxx']]);
        $this->assertTrue(true,is_array($user4->all()['items']));
        $this->assertTrue(true,is_integer($user4->all()['items']['status']));
        $this->assertArrayNotHasKey('status',$user4->all());
        $this->assertArrayNotHasKey('foo',$user4->all());

    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient20()
    {
        $user5 = new User5(['date'=>'2019-06-30']);
        $this->assertArrayNotHasKey('cgenerator1',$user5->all());
        $this->assertArrayNotHasKey('cgenerator2',$user5->all());
        $this->assertArrayNotHasKey('cgenerator3',$user5->all());
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient21()
    {
        $this->expectException(InvalidArgumentException::class);
        new User5(['code1'=>['asa']]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient22()
    {
        $this->expectException(InvalidArgumentException::class);
        new User5(['code1'=>[1,'asa']]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient23()
    {
        $this->expectException(InvalidArgumentException::class);
        $user5 = new User5(['code1'=>[1,2]]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient24()
    {
        $user5 = new User5(['code1'=>[123456,234567]]);
        $this->assertArrayHasKey('code1',$user5->all());
        $this->assertSame([123456,234567],$user5->all()['code1']);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient25()
    {
        $this->expectException(InvalidArgumentException::class);
        new User5(['code1'=>[[1]]]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient26()
    {
        $this->expectException(InvalidArgumentException::class);
        new User5(['code1'=>[[123456,'asa']]]);
    }

    /**
     * @throws ReflectionExceptionAlias
     */
    public function testClient27()
    {
        new User5(['code1'=>[[123456,234567]]]);
    }
}