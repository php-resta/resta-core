<?php

namespace Resta\Core\Tests\Config;

use PHPUnit\Framework\TestCase;
use Resta\Foundation\Application;

class ConfigTest extends TestCase
{
    /**
     * @var $app Application
     */
    protected $app;

    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        $this->app = new Application(false);

        $this->app->loadConfig(function()
        {
            return ['core'=>['test1'=>'foo','test2'=>'bar']];
        });

        parent::setUp();
    }

    /**
     * @return void|mixed
     */
    public function testBase()
    {
        $this->assertSame('foo',config('core.test1'));
    }
}