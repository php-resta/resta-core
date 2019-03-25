<?php

namespace Resta\Router;

use Resta\Support\Arr;
use Resta\Support\Utils;

class Route extends RouteHttpManager
{
    // get route acccessible property
    use RouteAccessiblePropertyTrait;

    /**
     * @var array
     */
    protected static $endpoints = [];

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
     * @var $namespace
     */
    protected static $namespace;

    /**
     * get route checkArrayEqual
     *
     * @param $patterns
     * @param $urlRoute
     * @return int|string
     */
    private static function checkArrayEqual($patterns,$urlRoute)
    {
        // calculates the equality difference between
        // the route pattern and the urlRoute value.
        foreach ($patterns as $key=>$pattern){

            if(Utils::isArrayEqual($pattern,$urlRoute)){
                return $key;
            }
        }

        // if the difference is not equal,
        // null is returned.
        return null;
    }

    /**
     * get route getPatternResolve
     *
     * @return array|int|string
     */
    private static function getPatternResolve()
    {
        $routes = self::getRoutes();

        if(!isset($routes['pattern'])){
            return [];
        }

        $patterns = $routes['pattern'];
        $urlRoute = array_filter(route(),'strlen');

        foreach ($patterns as $key=>$pattern){

            $pattern = array_filter($pattern,'strlen');
            $diff = Arr::arrayDiffKey($pattern,$urlRoute);

            if($diff){

                $matches = true;

                foreach ($pattern as $patternKey=>$patternValue){
                    if(!preg_match('@\{(.*?)\}@is',$patternValue)){
                        if($patternValue!==$urlRoute[$patternKey]){
                            $matches = false;
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
     * get route getRouteResolve
     *
     * @return array
     */
    public static function getRouteResolve()
    {
        // get routes data and the resolving pattern
        // Both are interrelated.
        $routes         = self::getRoutes();
        $patternResolve = self::getPatternResolve();

        //if routes data is available in pattern resolve.
        if(isset($routes['data'][$patternResolve])){

            // if the incoming http value is
            // the same as the real request method, the data is processed.
            if($routes['data'][$patternResolve]['http'] == strtolower(httpMethod)){

                // we are set the solved pattern to a variable.
                $resolve = $routes['data'][$patternResolve];

                return [
                    'class'         => $resolve['class'],
                    'method'        => $resolve['method'],
                    'controller'    => $resolve['controller'],
                    'namespace'     => $resolve['namespace']
                ];
            }
        }
        return [];
    }

    /**
     * route handle for route application
     *
     * @return void|mixed
     */
    public function handle()
    {
        // we will record the path data for the route.
        // We set the routeMapper variables and the route path.
        self::setPath(function(){

            // we are sending
            // the controller and routes.php path.
            return [
                'controllerPath'    => path()->controller(),
                'routePath'         => path()->route(),
            ];
        });

        // in the paths data,
        // we run the route mapper values ​​and the route files one by one.
       foreach (self::$paths as $mapper=>$controller){
           core()->fileSystem->callFile($mapper);
       }
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
            $routePrefix    = (defined('endpoint')) ? endpoint : md5(time());
            $routeName      = $routePrefix.'Route.php';
            $routeMapper    = $routeDefinitor['routePath'].''.DIRECTORY_SEPARATOR.''.$routeName;

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
        [$pattern,$route]   = $params;
        [$class,$method]    = explode("@",$route);

        $patternList = array_values(
            array_filter(explode("/",$pattern),'strlen')
        );

        static::$routes['pattern'][] = $patternList;
        static::$routes['data'][] = [
            'method'        => $method,
            'class'         => $class,
            'http'          => $function,
            'controller'    => $controller,
            'namespace'     => static::$namespace,
        ];
    }
}