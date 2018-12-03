<?php

namespace Resta\Config;

use phpDocumentor\Reflection\DocBlock\Tags\See;
use phpDocumentor\Reflection\Types\Self_;

class Config
{
    /**
     * @var $config
     */
    private static $config = null;

    /**
     * @var null $_instance
     */
    private static $_instance = null;

    /**
     * @var null
     */
    private static $configProcessInstance = null;

    /**
     * Config constructor.
     * @param null $config
     */
    public function __construct()
    {
        if(self::$config!==null && self::$configProcessInstance===null){
            self::$configProcessInstance=new ConfigProcess(self::$config);
        }

    }

    /**
     * @return string
     */
    public function get()
    {
        if(self::$configProcessInstance!==null){
            return self::$configProcessInstance->get();
        }

    }

    /**
     * @param $config
     * @return Config
     */
    public static function make($config=null)
    {
        if(self::$_instance===null){
            return (new self())->setConfig($config);
        }
        return new self();
    }

    /**
     * @param $config
     */
    public function setConfig($config)
    {
        self::$_instance=1;

        self::$config = $config;

        return new self();
    }
}