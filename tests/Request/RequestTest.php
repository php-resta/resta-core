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
     * test request
     *
     * @throws ReflectionExceptionAlias
     */
    public function testRequest()
    {
        $this->expectException(\UnexpectedValueException::class);
        new UserRequest(['a'=>'b']);

    }
}