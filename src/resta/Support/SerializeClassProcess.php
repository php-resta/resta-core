<?php

namespace Resta\Support;

use SuperClosure\Serializer;

class SerializeClassProcess
{
    /**
     * @var array
     */
    private static $serializes = [];

    /**
     * @var null|object
     */
    private static $singleton;

    /**
     * @return object|Serializer|null
     */
    private static function getInstance()
    {
        if(is_null(self::$singleton)){
            self::$singleton = new Serializer();
        }

        return self::$singleton;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        $closureResolve = self::getInstance()->unserialize($key);
        return $closureResolve();
    }

    /**
     * @param $key
     * @return string
     */
    public static function set($key)
    {
        return self::getInstance()->serialize(function() use($key){
            return app()->resolve($key);
        });
    }
}