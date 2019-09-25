<?php

namespace Resta\Core\Tests\Client\Data\User;

trait UserGenerator
{
    /**
     * auto generator for inputs
     * @var array
     */
    protected $generators = [];

    /**
     * generators dont overwrite
     *
     * @var array
     */
    protected $generators_dont_overwrite = [];
}