<?php

namespace Resta\Core\Tests\Container\Dummy;

class ContainerBindCallClass
{
    public function get(ContainerBindInterface $bind)
    {
        return $bind->get();
    }
}