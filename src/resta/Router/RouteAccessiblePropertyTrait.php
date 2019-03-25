<?php

namespace Resta\Router;

use Resta\Support\Utils;

trait RouteAccessiblePropertyTrait
{
    /**
     * get route mappers
     *
     * @return array
     */
    protected static function getMappers()
    {
        // this feature will give you a map
        // to see all your route methods.
        return static::$mappers;
    }

    /**
     * get static path
     *
     * @return array
     */
    protected static function getPath()
    {
        // this feature helps you to assign
        // which routes to run on your routes.
        return static::$paths;
    }

    /**
     * get static routes
     *
     * @return array
     */
    protected static function getRoutes()
    {
        // it collects and
        // executes route data in an array.
        return static::$routes;
    }

    /**
     * get static trace path
     *
     * @return mixed|null
     */
    protected static function getTracePath()
    {
        // detects where the route path is coming from
        // and returns this data in the static path.
        $trace = Utils::trace(2,'file');

        // the trace is returned if the variable is available
        // in the path data, otherwise it returns null.
        return static::getPath()[$trace] ?? null;
    }
}