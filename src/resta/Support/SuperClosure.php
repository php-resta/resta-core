<?php

namespace Resta\Support;

use SuperClosure\Serializer;

class SuperClosure
{
    /**
     * @var null|object
     */
    private static $singleton;

    /**
     * @return Serializer
     */
    public static function getInstance()
    {
        if(is_null(self::$singleton)){
            self::$singleton = new Serializer();
        }

        return self::$singleton;
    }

    public static function get($object)
    {

    }

    public static function set($data)
    {
        if(is_callable($data)){
            return self::getInstance()->serialize($data);
        }
        return self::getInstance()->serialize(function() use ($data){
            return $data;
        });
    }
}