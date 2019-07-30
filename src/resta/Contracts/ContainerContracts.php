<?php

namespace Resta\Contracts;

interface ContainerContracts
{
    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @param null|string $alias
     * @return mixed
     */
    public function bind($object=null,$callback=null);

    /**
     * @param $abstract
     * @return mixed
     */
    public function get($abstract);

    /**
     * @param $abstract
     * @return mixed
     */
    public function has($abstract);

    /**
     * @method kernel
     * @return mixed
     */
    public function kernel();

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @param $container false
     * @return mixed
     */
    public function make($object=null,$callback=null,$container=false);

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @return mixed
     */
    public function register($key,$object,$concrete=null);

    /**
     * @param null $object
     * @param null $callback
     * @return mixed
     */
    public function share($object=null,$callback=null);

    /**
     * @method singleton
     * @return mixed
     */
    public function singleton();

    /**
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function resolve($class,$bind=array());

    /**
     * @param $class
     * @return mixed
     */
    public function resolved($class);


}