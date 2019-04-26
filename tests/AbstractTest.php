<?php

namespace Resta\Core\Tests;

use PHPUnit\Framework\TestCase;
use Resta\Foundation\Application;
use Resta\Support\BootStaticManager;

abstract class AbstractTest extends TestCase
{
    /**
     * @var $app Application
     */
    protected static $app;

    /**
     * @return void|mixed
     */
    protected function setUp()
    {
        BootStaticManager::setPath([
            'core','test'
        ]);

        if(static::$app===null){
            static::$app = new Application(true);
        }

        parent::setUp();
    }

    /**
     * @return void|mixed
     */
    public function testApplicationInstance()
    {
        $this->assertInstanceOf(Application::class,static::$app);
    }
}