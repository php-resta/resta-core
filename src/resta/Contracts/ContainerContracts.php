<?php

namespace Resta\Contracts;

interface ContainerContracts {

    /**
     * @method bind
     * @param $object null
     * @param $callback null
     * @param $container false
     * @return mixed
     */
    public function bind($object=null,$callback=null,$container=false);


    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function container($object=null,$callback=null);

    /**
     * @method singleton
     * @return mixed
     */
    public function singleton();

    /**
     * @method kernel
     * @return mixed
     */
    public function kernel();

    /**
     * @param $eventName
     * @param $object
     * @return mixed
     */
    public function addEvent($eventName,$object);

    /**
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function resolve($class,$bind=array());


}