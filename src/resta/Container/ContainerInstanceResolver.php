<?php

namespace Resta\Container;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class ContainerInstanceResolver extends ApplicationProvider
{
    /**
     * @var object
     */
    protected $kernel;

    /**
     * ContainerInstanceResolver constructor.
     * @param ApplicationContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        parent::__construct($app);

        //get container values for kernel
        $this->kernel = $this->app->singleton();
    }

    /**
     * container resolve for instance
     *
     * @param null|string $name
     * @return array
     */
    public function container($name=null)
    {
        //check container value for kernel
        if(isset($this->kernel->kernel)){

            // if methoda is a null parameter,
            // then we send direct container values.
            if(is_null($name)){
                return (array)$this->kernel->kernel;
            }

            // if there is an existing value in the container as the method parameter,
            // we send this value directly in the container.
            if(isset($this->container()[$name])){
                return $this->container()[$name];
            }
        }
        return null;
    }

    /**
     * reflection resolve for instance
     *
     * @return mixed
     */
    public function reflection()
    {
        //we solve the reflection method with the resolve method.
        return app()->resolve($this->kernel->{__FUNCTION__});
    }

    /**
     * instance magic caller
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        //we call container instance as data
        return $this->container($name);
    }
}