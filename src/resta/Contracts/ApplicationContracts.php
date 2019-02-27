<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @return array
     */
    public function commandList();

    /**
     * @return mixed
     */
   public function console();

    /**
     * @return mixed
     */
   public function corePath();

    /**
     * @return mixed
     */
   public function detectEnvironmentForApplicationKey();

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
   public function kernelGroupKeys();

    /**
     * @param $maker
     * @return mixed
     */
    public function manifest($maker);

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @return mixed
     */
    public function register($key,$object,$concrete=null);

    /**
     * @return array
     */
    public function serviceProviders();

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