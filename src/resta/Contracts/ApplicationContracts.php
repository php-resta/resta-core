<?php

namespace Resta\Contracts;

interface ApplicationContracts
{
    /**
     * @method container
     * @param $object null
     * @param $callback null
     * @return mixed
     */
    public function bind($object=null,$callback=null);

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
     * @param string $config
     * @return mixed
     */
    public function config($config);

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
     * @param $abstract
     * @return mixed
     */
    public function get($abstract);

    /**
     * @return mixed
     */
   public function handle();

    /**
     * @param $abstract
     * @return mixed
     */
    public function has($abstract);

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
     * @method kernel
     * @return mixed
     */
    public function kernel();

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
     * @method bind
     * @param $object null
     * @param $callback null
     * @param $container false
     * @return mixed
     */
    public function make($object=null,$callback=null,$container=false);

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

    /**
     * @return mixed
     */
    public function runningInConsole();

    /**
     * @return array
     */
    public function serviceProviders();

    /**
     * @param string $name
     * @param string $path
     * @return mixed
     */
   public function setPaths($name,$path);

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
     * @return mixed
     */
   public function version();
}