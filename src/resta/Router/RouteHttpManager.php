<?php

namespace Resta\Router;

class RouteHttpManager
{
    /**
     * @param mixed ...$params
     */
    public static function delete(...$params)
    {
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * @param mixed ...$params
     */
    public static function get(...$params)
    {
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * set namespace for route
     *
     * @param $namespace
     * @return Route
     */
    public static function namespace($namespace)
    {
        static::$namespace = $namespace;

        return new static();
    }

    /**
     * http post method
     *
     * @param mixed ...$params
     */
    public static function post(...$params)
    {
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * http put method
     *
     * @param mixed ...$params
     */
    public static function put(...$params)
    {
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }
}