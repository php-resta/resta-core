<?php

namespace Resta\Core\Tests\Config;

use Resta\Config\Config;
use Resta\Core\Tests\AbstractTest;

class ConfigTest extends AbstractTest
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

        $configDirectory = root.''.DIRECTORY_SEPARATOR.'Config';

        if(!file_exists($configDirectory)){
            @mkdir($configDirectory);
            @touch($configDirectory.''.DIRECTORY_SEPARATOR.'App.php');
        }

        static::$app->loadConfig(function()
        {
           return root.''.DIRECTORY_SEPARATOR.'Config';
        },true);

        $this->values = [
            'test1'=>'foo',
            'test2'=>[
                'nested1'=>'nested1value'
            ]
        ];

        static::$app->loadConfig(function()
        {
            return ['core' => $this->values];
        });
    }

    /**
     * @return void|mixed
     */
    public function testConfigHelper()
    {
        $this->assertSame('foo',config('core.test1'));
        $this->assertSame('nested1value',config('core.test2.nested1'));
    }

    /**
     * @return void|mixed
     */
    public function testGetValues()
    {
        $this->assertSame(null,config('core-not'));
        $this->assertSame(null,config('core.not-exist'));
        $this->assertSame($this->values,config('core'));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testSetValues()
    {
        $this->assertTrue(true,Config::make('app')->set([
            'x'=>'y',
            'x2'=>'y2'
        ]));

        $this->assertSame('y',Config::make('app.x')->get());
        $this->assertSame('y2',config('app.x2'));

        $this->assertNull(null,Config::make('app')->set([
            'x'=>'y',
            'x2'=>'y2'
        ]));
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function testConfigFacade()
    {
        $this->assertInstanceOf(Config::class,Config::make());

        $this->assertSame('foo',Config::make('core.test1')->get());
        $this->assertSame('nested1value',Config::make('core.test2.nested1')->get());
    }

}