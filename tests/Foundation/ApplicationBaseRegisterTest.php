<?php

namespace Resta\Core\Tests\Foundation;

use Resta\Contracts\ApplicationContracts;
use Resta\Core\Tests\AbstractTest;
use Resta\Foundation\Application;

class ApplicationBaseRegisterTest extends AbstractTest
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void|mixed
     */
    public function testApplicationBaseRegister()
    {
        $this->assertTrue(true,defined('appInstance'));
        $this->assertTrue(true,isset(static::$app['revision']));
        $this->assertTrue(true,is_array(static::$app['revision']));
        $this->assertTrue(true,isset(static::$app['corePath']));
        $this->assertTrue(true,isset(static::$app['container']));
        $this->assertTrue(true,isset(static::$app['appClass']));
        $this->assertTrue(true,isset(static::$app['appClosureInstance']));
        $this->assertTrue(true,isset(static::$app['closureBootLoader']));
        $this->assertTrue(true,isset(static::$app['macro']));
        $this->assertTrue(true,isset(static::$app['instanceController']));
        $this->assertTrue(true,isset(static::$app['responseSuccess']));
        $this->assertTrue(true,isset(static::$app['responseStatus']));
        $this->assertTrue(true,isset(static::$app['responseType']));
        $this->assertTrue(true,isset(static::$app['out']));
        $this->assertTrue(true,isset(static::$app['request']));
        $this->assertTrue(true,isset(static::$app['get']));
        $this->assertTrue(true,isset(static::$app['post']));
        $this->assertTrue(true,isset(static::$app['httpMethod']));
        $this->assertTrue(true,isset(static::$app['fileSystem']));
        $this->assertTrue(true,defined('httpMethod'));

    }

}