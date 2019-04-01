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
     * container resolve for instance
     *
     * @return array
     */
    public function container($name=null)
    {
        //check container value for kernel
        if(isset($this->instances['container'])){

            // if methoda is a null parameter,
            // then we send direct container values.
            if($name===null){
                return (array)$this->instances['container'];
            }

            // if there is an existing value in the container as the method parameter,
            // we send this value directly in the container.
            if(isset($this->container()[$name])){
                return $this->container()[$name];
            }

        }
        return [];
    }

    /**
     * reflection resolve for instance
     *
     * @return mixed
     */
    public function reflection()
    {
        //we solve the reflection method with the resolve method.
        return app()->resolve($this->instances[__FUNCTION__]);
    }

    /**
     * instance magic caller
     *
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        //we call container instance as data
        return $this->instances[$name] ?? $this->container($name);
    }
}