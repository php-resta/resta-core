<?php

namespace Resta\Router;

use Resta\Support\Utils;

trait RouteAccessiblePropertyTrait
{
    /**
     * get mappers
     *
     * @return array
     */
    public static function getMappers()
    {
        return static::$mappers;
    }

    /**
     * get static getPath
     *
     * @return array
     */
    public static function getPath()
    {
        return static::$paths;
    }

    /**
     * get static getRoutes
     *
     * @return array
     */
    public static function getRoutes()
    {
        return static::$routes;
    }

    /**
     * get static getTracePath
     *
     * @return mixed|null
     */
    public static function getTracePath()
    {
        $trace = Utils::trace(2,'file');
        return static::getPath()[$trace] ?? null;
    }
}