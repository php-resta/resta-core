<?php

namespace Resta\Contracts;

interface ApplicationContracts {

    /**
     * @param $object
     * @return mixed
     */
    public function checkBindings($object);

    /**
     * @param $command
     * @param array $arguments
     * @return mixed
     */
    public function command($command,$arguments=array());

    /**
     * @return array
     */
    public function commandList();

    /**
     * @param null $config
     * @return mixed
     */
    public function config($config=null);

    /**
     * @return mixed
     */
   public function console();

    /**
     * @return mixed
     */
   public function corePath();

    /**
     * @param array $environment
     * @return mixed
     */
   public function environment($environment=array());

    /**
     * @return mixed
     */
   public function handle();

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
     * @return array
     */
   public function kernelGroupList();

    /**
     * @param callable $callback
     * @return mixed
     */
    public function loadConfig(callable $callback);

    /**
     * @param array $loaders
     * @return mixed
     */
    public function loadIfNotExistBoot($loaders=array());

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
     * @return mixed
     */
    public function runningInConsole();

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