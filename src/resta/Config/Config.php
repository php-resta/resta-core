<?php

namespace Resta\Config;

class Config
{
    /**
     * @var null $config
     */
    private static $config = null;

    /**
     * @var null $configProcessInstance
     */
    private static $configProcessInstance = null;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        if(self::$config!==null && self::$configProcessInstance===null){
            self::$configProcessInstance=new ConfigProcess();
        }
    }

    /**
     * @return mixed|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get()
    {
        if(self::$configProcessInstance!==null){

            $config = self::$config;

            self::$configProcessInstance->offsetSet('config',$config);

            return self::$configProcessInstance->get();
        }

        return null;
    }

    /**
     * @param null $config
     * @return Config
     */
    public static function make($config=null)
    {
        return (new self())->setConfig($config);
    }

    /**
     * @param $config
     * @return Config
     */
    public function setConfig($config)
    {
        self::$config = $config;

        return new self();
    }
}