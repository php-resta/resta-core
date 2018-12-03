<?php

namespace Resta\Config;

use phpDocumentor\Reflection\Types\Self_;

class Config
{
    /**
     * @var $config
     */
    private static $config;

    /**
     * @var null $_instance
     */
    private static $_instance = null;

    /**
     * @var $configProcessInstance ConfigProcess
     */
    private static $configProcessInstance;

    /**
     * Config constructor.
     * @param null $config
     */
    public function __construct($config=null)
    {
        if($config!==null && self::$configProcessInstance===null){
            self::$configProcessInstance=new ConfigProcess($config);
        }
    }

    /**
     * @return mixed|null
     */
    public function get()
    {
        if(self::$configProcessInstance!==null){
            return self::$configProcessInstance->get();
        }
        return null;

    }

    /**
     * @param $config
     * @return Config
     */
    public static function make($config=null)
    {
        if (self::$_instance === null && ($config===null OR $config!==self::$config)) {
            self::$config = $config;
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }
}