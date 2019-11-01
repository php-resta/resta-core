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
            static::$app = new Application(true,__DIR__.'/../Config/service.json');
        }

        $configDirectory = root.''.DIRECTORY_SEPARATOR.'Config';

        if(!file_exists($configDirectory)){
            @mkdir($configDirectory);
            @touch($configDirectory.''.DIRECTORY_SEPARATOR.'Core.php');
        }

        //set config path
        static::$app->setPaths('config',$configDirectory);

        if(!file_exists($containerCacheFile = static::$app->containerCacheFile())){
            files()->put($containerCacheFile,'[]');
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