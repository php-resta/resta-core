<?php

namespace Resta\Routing;

use Resta\Support\Arr;

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
     * @return void|mixed
     */
    public function handle()
    {
       foreach (self::$paths as $path){
           if(file_exists($path)){
               resta()->fileSystem->callFile($path);
           }
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
     * @param $function
     */
    public static function setRoute($params,$function)
    {
        [$pattern,$route] = $params;

        [$class,$method] = explode("@",$route);

        $patternList = array_slice(explode("/",$pattern),1);

        static::$routes['pattern'][] = $patternList;
        static::$routes['data'][] = ['method'=>$method,'class'=>$class,'http'=>$function];
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
                    'class'     => $resolve['class'],
                    'method'    => $resolve['method'],
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
        $urlRoute   = route();

        if(isset($urlRoute[0]) && $urlRoute[0]==""){
            $urlRoute = [];
        }

        foreach ($patterns as $key=>$pattern){

            if(isset($pattern[0]) && $pattern[0]==""){
                $pattern = [];
            }

            $diff = Arr::arrayDiffKey($pattern,$urlRoute);

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
                    return $key;
                }
            }

            if(count($pattern)-1 == count(route())){
                if(preg_match('@\{[a-z]+\?\}@is',end($pattern))){
                    return $key;
                }
            }
        }
    }
}