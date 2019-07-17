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
    public static function getMappers() : array
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
    protected static function getPath() : array
    {
        // this feature helps you to assign
        // which routes to run on your routes.
        return static::$paths;
    }

    /**
     * get routes
     *
     * @param null|string $method
     * @return array
     */
    public static function getRoutes($method=null) : array
    {
        // it collects and
        // executes route data in an array.
        if(is_null($method)){
            return static::$routes;
        }

        $httpRouteList = [];

        $routes = self::getRoutes();

        if(isset($routes['data'])){
            foreach ($routes['data'] as $key=>$item){
                if($item['http']==httpMethod()){
                    $httpRouteList['data'][$key] = $item;
                }
            }
        }

        if(isset($routes['pattern'],$httpRouteList['data'])){
            foreach ($httpRouteList['data'] as $key=>$item){
                $httpRouteList['pattern'][$key] = $routes['pattern'][$key];
            }
        }

        return $httpRouteList;
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