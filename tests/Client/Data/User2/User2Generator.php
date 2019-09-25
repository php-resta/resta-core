<?php

namespace Resta\Core\Tests\Client\Data\User2;

trait User2Generator
{
    /**
     * auto generator for inputs
     * @var array
     */
    protected $generators = ['test','test2','test3'];

    /**
     * generators dont overwrite
     *
     * @var array
     */
    protected $generators_dont_overwrite = ['test','test3'];

    public function testGenerator()
    {
        return 1;
    }

    public function test2Generator()
    {
        return 'test2';
    }

    public function test3Generator()
    {
        return 'test3';
    }
}