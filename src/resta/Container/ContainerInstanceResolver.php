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

            //in the instances we control the register data.
            if(isset($this->instances['register'])){

                // with tap helper method,
                // we save the data to the global accessor.
                tap($this->instances['register'],function($instance) use($key,$object,$concrete){
                    return $instance->register($key,$object,$concrete);
                });
            }

            // this method records the kernel container object globally.
            // registered objects can be easily accessed with the resta () assistant.
            if(isset(resta()->bound)){
                return resta()->bound->register($key,$object,$concrete);
            }
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