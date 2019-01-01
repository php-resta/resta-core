<?php

namespace Resta\Routing;

class Route
{
    /**
     * @var array
     */
    protected static $routes = [];

    /**
     * @var array
     */
    protected static $paths = [];

    /**
     * @param mixed ...$params
     */
    public static function delete(...$params)
    {
        self::setRoute($params,__FUNCTION__);
    }

    /**
     * @param mixed ...$params
     */
    public static function get(...$params)
    {
        self::setRoute($params,__FUNCTION__);
    }

    /**
     * @return array
     */
    public static function getPath()
    {
        return static::$paths;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return static::$routes;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
       foreach (self::$paths as $path){
           require_once $path;
       }
    }

    /**
     * @param mixed ...$params
     */
    public static function post(...$params)
    {
        self::setRoute($params,__FUNCTION__);
    }

    /**
     * @param mixed ...$params
     */
    public static function put(...$params)
    {
        self::setRoute($params,__FUNCTION__);
    }

    /**
     * @param $path
     */
    public static function setPath($path)
    {
        if(!isset(static::$paths[$path])){
            static::$paths[]=$path;
        }
    }

    /**
     * @param $params
     */
    public static function setRoute($params,$function)
    {
        [$pattern,$route] = $params;

        [$class,$method] = explode("@",$route);

        static::$routes[$class][$function][$method]['pattern'] = $pattern;
    }
}