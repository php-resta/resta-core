<?php

namespace Resta\Routing;

use Resta\Support\Arr;
use Resta\Support\Utils;

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
        self::setRoute($params,__FUNCTION__,self::getTracePath());
    }

    /**
     * @param mixed ...$params
     */
    public static function get(...$params)
    {
        self::setRoute($params,__FUNCTION__,self::getTracePath());
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
     * @return mixed|null
     */
    public static function getTracePath()
    {
        $trace = Utils::trace(2,'file');
        return self::getPath()[$trace] ?? null;
    }

    /**
     * @return void|mixed
     */
    public function handle()
    {
       foreach (self::$paths as $mapper=>$controller){
           if(file_exists($mapper)){
               core()->fileSystem->callFile($mapper);
           }
       }
    }

    /**
     * @param mixed ...$params
     */
    public static function post(...$params)
    {
        self::setRoute($params,__FUNCTION__,self::getTracePath());
    }

    /**
     * @param mixed ...$params
     */
    public static function put(...$params)
    {
        self::setRoute($params,__FUNCTION__,self::getTracePath());
    }

    /**
     * @param $path
     */
    public static function setPath(callable $callback)
    {
        $routeDefinitor = call_user_func($callback);

        if(!isset(static::$paths[$routeDefinitor['routeMapper']])){
            static::$paths[$routeDefinitor['routeMapper']]=$routeDefinitor['controllerPath'];
        }
    }

    /**
     * @param $params
     * @param $function
     * @param null $controller
     */
    public static function setRoute($params,$function,$controller=null)
    {
        [$pattern,$route] = $params;

        [$class,$method] = explode("@",$route);

        $patternList = array_values(
            array_filter(explode("/",$pattern),'strlen')
        );

        static::$routes['pattern'][] = $patternList;
        static::$routes['data'][] = [
            'method'        => $method,
            'class'         => $class,
            'http'          => $function,
            'controller'    => $controller,
        ];
    }

    /**
     * @return array
     */
    public static function getRouteResolve()
    {
        $routes         = self::getRoutes();
        $patternResolve = self::getPatternResolve();

        if(isset($routes['data'][$patternResolve])){

            if($routes['data'][$patternResolve]['http'] == strtolower(httpMethod)){

                $resolve = $routes['data'][$patternResolve];

                return [
                    'class'         => $resolve['class'],
                    'method'        => $resolve['method'],
                    'controller'    => $resolve['controller'],
                ];
            }
        }
        return [];
    }

    /**
     * @return array|int|string
     */
    private static function getPatternResolve()
    {
        $routes     = self::getRoutes();

        if(!isset($routes['pattern'])){
            return [];
        }

        $patterns   = $routes['pattern'];
        $urlRoute   = array_filter(route(),'strlen');

        foreach ($patterns as $key=>$pattern){

            $pattern = array_filter($pattern,'strlen');
            $diff    = Arr::arrayDiffKey($pattern,$urlRoute);

            if($diff){

                $matches=true;

                foreach ($pattern as $patternKey=>$patternValue){
                    if(!preg_match('@\{(.*?)\}@is',$patternValue)){
                        if($patternValue!==$urlRoute[$patternKey]){
                            $matches=false;
                        }
                    }
                }

                if($matches){

                    $isArrayEqual = self::checkArrayEqual($patterns,$urlRoute);

                    if($isArrayEqual===null){
                        return $key;
                    }
                    return $isArrayEqual;

                }
            }

            if(count($pattern)-1 == count(route())){
                if(preg_match('@\{[a-z]+\?\}@is',end($pattern))){
                    return $key;
                }
            }
        }
    }

    /**
     * @param $patterns
     * @param $urlRoute
     * @return int|string
     */
    private static function checkArrayEqual($patterns,$urlRoute)
    {
        foreach ($patterns as $key=>$pattern){

            if(Utils::isArrayEqual($pattern,$urlRoute)){
                return $key;
            }
        }

        return null;
    }
}