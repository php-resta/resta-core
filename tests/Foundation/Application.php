<?php

namespace Resta\Core\Tests\Foundation;

use Resta\Core\Tests\AbstractTest;

class Application extends AbstractTest
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
}