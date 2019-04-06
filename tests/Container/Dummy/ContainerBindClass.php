<?php

namespace Resta\Core\Tests\Container\Dummy;

class ContainerBindClass implements ContainerBindInterface
{
    public function get()
    {
        return true;
    }
}