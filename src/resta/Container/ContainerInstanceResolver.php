<?php

namespace Resta\Container;

class ContainerInstanceResolver
{
    /**
     * @var $instances
     */
    protected $instances;

    /**
     * ContainerInstanceResolver constructor.
     * @param $instances
     */
    public function __construct($instances)
    {
        //get container instances
        $this->instances = $instances;
    }

    /**
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        //we call container instance as data
        return $this->instances[$name] ?? null;
    }
}