<?php

namespace Resta\Core\Tests\Foundation;

use Resta\Support\Dependencies;
use Resta\Core\Tests\AbstractTest;

class ApplicationTest extends AbstractTest
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
    public function testApplicationKernelGroupKeys()
    {
        $this->assertSame([
            'originGroups',
            'consoleGroups',
            'middlewareGroups',
            'reflectionGroups',
        ],static::$app->kernelGroupKeys());
    }

    /**
     * @return void|mixed
     */
    public function testCorePath()
    {
        $this->assertNotNull(true,static::$app->corePath());
    }

    /**
     * @return void|mixed
     */
    public function testCheckDependenciesForKernelGroupList()
    {
        $kernelGroupList = static::$app->kernelGroupList();

        foreach(Dependencies::getBootLoaders() as $loader){

            $this->assertTrue(true,isset($kernelGroupList[$loader]));
        }
    }
}