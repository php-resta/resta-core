<?php

namespace Resta\Router;

use http\Exception;

abstract class RouteHttpManager
{
    /**
     * http delete method
     *
     * @param mixed ...$params
     * @return void
     */
    public static function delete(...$params) : void
    {
        // the DELETE method deletes the specified resource.
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * http get method
     *
     * @param mixed ...$params
     * @return void
     */
    public static function get(...$params) : void
    {
        // the GET method requests a representation of the specified resource.
        // Requests using GET should only retrieve data.
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * set namespace for route
     *
     * @param $namespace
     * @return Route
     */
    public static function namespace($namespace) : Route
    {
        // this feature is ideal for detecting
        // a directory with a reachable endpoint.
        static::$namespace = $namespace;

        // the method returns
        // the class itself.
        return new static();
    }

    /**
     * set namespace for route
     *
     * @param $namespace
     * @return Route
     */
    public static function module($module) : Route
    {
        // this feature is ideal for detecting
        // a directory with a reachable endpoint.
        static::$module = $module;

        // the method returns
        // the class itself.
        return new static();
    }

    /**
     * http post method
     *
     * @param mixed ...$params
     * @return void
     */
    public static function post(...$params) : void
    {
        // the POST method is used to submit an entity to the specified resource,
        // often causing a change in state or side effects on the server.
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * http put method
     *
     * @param mixed ...$params
     * @return void
     */
    public static function put(...$params) : void
    {
        // the PUT method replaces all current representations of
        // the target resource with the request payload.
        static::setRoute($params,__FUNCTION__,static::getTracePath());
    }

    /**
     * this trait gives an error when calling from outside.
     *
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments) : Exception
    {
        // this abstract class is automatically returned
        // to the exception in the use of any class other than the route class.
        exception()->badFunctionCall($name.' method is not valid for routing process');
    }
}