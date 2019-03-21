<?php

namespace Resta\Router;

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
     * @var array $mappers
     */
    protected static $mappers = [];

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
     * get controller router
     *
     * @return mixed|void
     */
    public static function getControllerRouter()
    {
        define ('getControllerRouter' ,true);

        self::setPath(function(){

            // as controller path,
            // we check whether the endpoint constant is loaded.
            if(defined('endpoint')){

                // we will record the path data for the route.
                // We set the routeMapper variables and the route path.
                Route::setPath(function(){

                    // we are sending the controller and routes.php path.
                    return [
                        'controllerPath'    => path()->controller(endpoint,true),
                        'routePath'         => config('kernel.paths.route'),
                    ];
                });
            }
        });
    }

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
        return self::getPath()[$trace] ?? null;
    }

    /**
     * route handle for application
     *
     * @return void|mixed
     */
    public function handle()
    {
        // we will record the path data for the route.
        // We set the routeMapper variables and the route path.
        self::getControllerRouter();

       foreach (self::$paths as $mapper=>$controller){
           core()->fileSystem->callFile($mapper);
       }
    }

    /**
     * http post method
     *
     * @param mixed ...$params
     */
    public static function post(...$params)
    {
        self::setRoute($params,__FUNCTION__,self::getTracePath());
    }

    /**
     * http put method
     *
     * @param mixed ...$params
     */
    public static function put(...$params)
    {
        self::setRoute($params,__FUNCTION__,self::getTracePath());
    }

    /**
     * get route setPath method
     *
     * @param $path
     */
    public static function setPath(callable $callback)
    {
        $routeDefinitor = call_user_func($callback);

        if(isset($routeDefinitor['controllerPath']) && isset($routeDefinitor['routePath'])){

            //the route paths to be saved to the mappers static property.
            static::$mappers['routePaths'][] = $routeDefinitor['routePath'];
            static::$mappers['controllerNamespaces'][] = Utils::getNamespace($routeDefinitor['controllerPath']);

            //set a predefined value for route.php.
            $routePrefix = (defined('endpoint')) ? endpoint : '';

            $routeName = isset($routeDefinitor['routeName'])
                ? $routeDefinitor['routeName']
                : $routePrefix.'Route.php';

            $routeMapper = $routeDefinitor['routePath'].''.DIRECTORY_SEPARATOR.''.$routeName;

            if(file_exists($routeMapper) && !isset(static::$paths[$routeMapper])){
                static::$paths[$routeMapper] = $routeDefinitor['controllerPath'];
            }
        }
    }

    /**
     * get route setRoute method
     *
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
     * get route getRouteResolve
     *
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
     * get route getPatternResolve
     *
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
     * get route checkArrayEqual
     *
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