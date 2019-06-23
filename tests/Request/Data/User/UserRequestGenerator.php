<?php

namespace Resta\Core\Tests\Request\Data\User;

trait UserRequestGenerator
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