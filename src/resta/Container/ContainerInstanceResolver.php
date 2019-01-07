<?php

namespace Resta\Container;

class ContainerInstanceResolver
{
    /**
     * @var $instances
     */
    protected $instances;

    /**
     * @var $offSet
     */
    protected $offSet;


    /**
     * ContainerInstanceResolver constructor.
     * @param $instances
     */
    public function __construct($instances)
    {
        //get container instances
        $this->instances    = $instances;
    }

    /**
     * application data register
     *
     * @return \Closure
     */
    public function register()
    {
        // in the instance array,
        // we will register a global data accessor using
        // the register method of the registerAppBound object.
        return function($key,$object,$concrete){
            return $this->instances['register']->register($key,$object,$concrete);
        };
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