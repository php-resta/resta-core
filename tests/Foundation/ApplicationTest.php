<?php

namespace Resta\Core\Tests\Foundation;

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
}