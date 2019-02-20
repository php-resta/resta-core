<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @return mixed
     */
   public function console();

    /**
     * @return mixed
     */
   public function handle();

    /**
     * @param callable $callback
     * @return mixed
     */
   public function loadConfig(callable $callback);

    /**
     * @param $abstract
     * @param $instance
     * @return mixed
     */
   public function instance ($abstract,$instance);

    /**
     * @return mixed
     */
   public function isLocale();

    /**
     * @return array
     */
   public function kernelGroups();

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @return mixed
     */
    public function register($key,$object,$concrete=null);

    /**
     * @param null $name
     * @param null $path
     * @return mixed
     */
   public function setPaths($name=null,$path=null);

    /**
     * @return mixed
     */
   public function version();

}