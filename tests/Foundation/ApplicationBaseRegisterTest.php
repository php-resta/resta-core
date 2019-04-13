<?php

namespace Resta\Core\Tests\Foundation;

use Resta\Core\Tests\AbstractTest;

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
        $this->assertTrue(true,isset($this->app['revision']));
        $this->assertTrue(true,isset($this->app['corePath']));
        $this->assertTrue(true,isset($this->app['container']));
        $this->assertTrue(true,isset($this->app['appClass']));
        $this->assertTrue(true,isset($this->app['appClosureInstance']));
        $this->assertTrue(true,isset($this->app['closureBootLoader']));
        $this->assertTrue(true,isset($this->app['macro']));
        $this->assertTrue(true,isset($this->app['instanceController']));
        $this->assertTrue(true,isset($this->app['responseSuccess']));
        $this->assertTrue(true,isset($this->app['responseStatus']));
        $this->assertTrue(true,isset($this->app['responseType']));
        $this->assertTrue(true,isset($this->app['out']));
        $this->assertTrue(true,isset($this->app['request']));
        $this->assertTrue(true,isset($this->app['get']));
        $this->assertTrue(true,isset($this->app['post']));
        $this->assertTrue(true,isset($this->app['httpMethod']));
        $this->assertTrue(true,isset($this->app['fileSystem']));
        $this->assertTrue(true,defined('httpMethod'));

    }

}