<?php

namespace Resta\Core\Tests\Client\Data;

trait ClientGenerator
{
    /**
     * auto generator for inputs
     * @var array
     */
    protected $auto_generators = ['cgenerator1','cgenerator2','cgenerator3'];

    /**
     * auto generators dont overwrite
     *
     * @var array
     */
    protected $auto_generators_dont_overwrite = ['cgenerator1','cgenerator3'];

    public function cgenerator1Generator()
    {
        return 'cgenerator1';
    }

    public function cgenerator2Generator()
    {
        return 'cgenerator2';
    }

    public function cgenerator3Generator()
    {
        return 'cgenerator3';
    }
}