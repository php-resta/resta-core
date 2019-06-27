<?php

namespace Resta\Router;

use Resta\Support\Arr;
use Resta\Support\Utils;
use Resta\Foundation\PathManager\StaticPathList;

class Route extends RouteHttpManager
{
    // get route acccessible property
    use RouteAccessiblePropertyTrait;

    /**
     * @var array $endpoints
     */
    protected static $endpoints = [];

    /**
     * @var array $routes
     */
    protected static $routes = [];

    /**
     * @var array $paths
     */
    protected static $paths = [];

    /**
     * @var array $mappers
     */
    protected static $mappers = [];

    /**
     * @var null|string $namespace
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
     * matching url method
     *
     * @param $patterns
     * @param $urlMethod
     * @return array
     */
    private static function matchingUrlMethod($patterns,$urlMethod)
    {
        if(isset($urlMethod[0])){

            $list = [];

            foreach ($patterns as $key=>$pattern){

                // if the initial value of the pattern data is present
                // and the first value from urlmethod does not match
                // and does not match the custom regex variable,
                // we empty the contents of the data.
                if(isset($pattern[0])){
                    if($pattern[0] !== $urlMethod[0] && !self::isMatchVaribleRegexPattern($pattern[0])){
                        $list[$key] = [];
                    }
                }

                // if the contents of the directory are not normally emptied,
                // we continue to save the list according to keyin status.
                if(!isset($list[$key])){
                    $list[$key] = $pattern;
                }

                // This is very important.
                // Route matches can be variable-based or static string-based.
                // In this case, we remove the other matches based on the static string match.
                if(isset($pattern[0]) && $pattern[0]==$urlMethod[0]){

                    // static matches will not be deleted retrospectively.
                    // this condition will check this.
                    if(isset($list[$key-1],$list[$key-1][0]) && $list[$key-1][0]!==$urlMethod[0]){
                        unset($list[$key-1]);
                    }

                    $list[$key] = $pattern;
                }
            }

            $patterns = $list;
        }

        return $patterns;
    }

    /**
     * get route pattern resolve
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

        $patternList = [];

        foreach($routes['data'] as $patternKey=>$routeData){
            if($routeData['http']==httpMethod()){
                $patternList[$patternKey]=$patterns[$patternKey];
            }
        }

        $patternList = self::matchingUrlMethod($patternList,$urlRoute);

        foreach ($patternList as $key=>$pattern){

            $pattern = array_filter($pattern,'strlen');
            $diff = Arr::arrayDiffKey($pattern,$urlRoute);

            if($diff){

                $matches = true;

                foreach ($pattern as $patternKey=>$patternValue){
                    if(!self::isMatchVaribleRegexPattern($patternValue)){
                        if($patternValue!==$urlRoute[$patternKey]){
                            $matches = false;
                        }
                    }
                }

                if($matches){

                    $isArrayEqual = self::checkArrayEqual($patternList,$urlRoute);

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
                    'namespace'     => $resolve['namespace'],
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

            // if there is endpoint,
            // then only that endpoint is transferred into the path
            if(defined('endpoint')){

                $routeName      = endpoint.'Route.php';
                $routeMapper    = $routeDefinitor['routePath'].''.DIRECTORY_SEPARATOR.''.$routeName;

                if(file_exists($routeMapper) && !isset(static::$paths[$routeMapper])){
                    static::$paths[$routeMapper] = $routeDefinitor['controllerPath'];
                }
            }
            else{

                // if there is no endpoint,
                // all files in the path of the route are transferred to path.
                $allFilesInThatRoutePath = Utils::glob($routeDefinitor['routePath']);

                foreach ($allFilesInThatRoutePath as $item){
                    static::$paths[$item] = $routeDefinitor['controllerPath'];
                }
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
            'endpoint'      => strtolower(str_replace(StaticPathList::$controllerBundleName,'',static::$namespace))
        ];
    }

    /**
     * is route variable regex pattern
     *
     * @param null $value
     * @return bool
     */
    public static function isMatchVaribleRegexPattern($value=null)
    {
        // determines if the variable that can be used
        // in the route file meets the regex rule.
        return (preg_match('@\{(.*?)\}@is',$value)) ? true : false;
    }
}