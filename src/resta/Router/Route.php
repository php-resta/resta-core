<?php

namespace Resta\Router;

use Resta\Support\Str;
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
    public static function checkArrayEqual($patterns,$urlRoute)
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
     * get route controller class
     *
     * @return string
     */
    public static function getRouteControllerClass()
    {
        $route = self::getRouteResolve();

        if(isset($route['class'])){
            return $route['class'];
        }

        return null;
    }

    /**
     * get route controller class
     *
     * @return string
     */
    public static function getRouteControllerMethod()
    {
        $route = self::getRouteResolve();

        if(isset($route['method'])){
            return $route['method'];
        }

        return null;
    }

    /**
     * get route controller namespace
     *
     * @return string
     */
    public static function getRouteControllerNamespace()
    {
        $route = self::getRouteResolve();

        if(isset($route['controller'],$route['namespace'])){
            return $route['controller'].'/'.$route['namespace'];
        }

        return null;
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
        $routes = self::getRoutes();
        $patternResolve = app()->resolve(RouteMatching::class,['route'=>new self()])->getPatternResolve();

        // we set the route variables for the route assistant.
        self::updateRouteParameters($patternResolve);

        //if routes data is available in pattern resolve.
        if(isset($routes['data'][$patternResolve])){

            // if the incoming http value is
            // the same as the real request method, the data is processed.
            if($routes['data'][$patternResolve]['http'] == strtolower(httpMethod)){

                // we are set the solved pattern to a variable.
                $resolve = $routes['data'][$patternResolve];

                $routeResolvedData = [
                    'class'         => $resolve['class'],
                    'method'        => $resolve['method'],
                    'controller'    => $resolve['controller'],
                    'namespace'     => $resolve['namespace'],
                    'http'          => httpMethod(),
                ];

                app()->register('routeResolvedData',$routeResolvedData);

                return $routeResolvedData;
            }
        }

        return [];
    }

    /**
     * update route parameters
     *
     * @param $patternResolvedKey
     * @return mixed|void
     */
    private static function updateRouteParameters($patternResolvedKey)
    {
        $list = [];

        if(isset(static::$routes['pattern'][$patternResolvedKey])){

            $routeParameters = static::$routes['pattern'][$patternResolvedKey];
            $route = route();

            foreach($routeParameters as $key=>$param){

                $param = Str::replaceWordArray(['{','}','?'],'',$param);

                if(isset($route[$key])){
                    $list[$param] = $route[$key];
                }
            }
        }

        app()->register('routeParams',$list);
    }

    /**
     * route handle for route application
     *
     * @param bool $endpoint
     */
    public function handle($endpoint=true)
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
        },$endpoint);

        // in the paths data,
        // we run the route mapper values ​​and the route files one by one.
        foreach (self::$paths as $mapper=>$controller){
            core()->fileSystem->callFile($mapper);
        }
    }

    /**
     * get route set path
     *
     * @param callable $callback
     * @param bool $endpoint
     */
    public static function setPath(callable $callback,$endpoint=true)
    {
        $routeDefinitor = call_user_func($callback);

        if(isset($routeDefinitor['controllerPath']) && isset($routeDefinitor['routePath'])){

            //the route paths to be saved to the mappers static property.
            static::$mappers['routePaths'][] = $routeDefinitor['routePath'];
            static::$mappers['controllerNamespaces'][] = Utils::getNamespace($routeDefinitor['controllerPath']);

            // if there is endpoint,
            // then only that endpoint is transferred into the path
            if(defined('endpoint') && $endpoint){

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


    /**
     * is optional route variable regex pattern
     *
     * @param null $value
     * @return bool
     */
    public static function isOptionalVaribleRegexPattern($value=null)
    {
        // determines if the variable that can be used
        // in the route file meets the regex rule.
        return preg_match('@\{[a-z]+\?\}@is',$value) ? true : false;
    }

    /**
     * get route mappings
     *
     * @return array
     */
    public static function getRouteMappings()
    {
        (new self())->handle(false);

        $mappings = [];

        $routes = self::getRoutes();
        $routeData = isset($routes['data']) ? $routes['data'] : [];
        $routePattern = isset($routes['pattern']) ? $routes['pattern'] : [];

        $counter = 0;

        $application = app();

        $application->loadIfNotExistBoot(['middleware']);
        $middlewareProvider = $application['middleware'];

        foreach($routeData as $key=>$data) {

            $middlewareProvider->setKeyOdds('endpoint', $data['endpoint']);
            $middlewareProvider->setKeyOdds('method', $data['method']);
            $middlewareProvider->setKeyOdds('http', $data['http']);

            $middlewareProvider->handleMiddlewareProcess();
            $beforeMiddleware = $middlewareProvider->getShow();

            $middlewareProvider->handleMiddlewareProcess('after');
            $afterMiddleware = $middlewareProvider->getShow();

            $endpoint = $data['endpoint'];
            $controllerNamespace = Utils::getNamespace($data['controller'] . '/' . $data['namespace'] . '/' . $data['class']);

            $methodDocument = app()['reflection']($controllerNamespace)->reflectionMethodParams($data['method'])->document;

            $methodDefinition = '';

            if (preg_match('@#define:(.*?)\n@is', $methodDocument, $definition)) {
                if (isset($definition[1])) {
                    $methodDefinition = rtrim($definition[1]);
                }
            }

            $mappings[$key]['endpoint'] = $endpoint . '/' . implode("/", $routePattern[$key]);
            $mappings[$key]['http'] = $data['http'];
            $mappings[$key]['definition'] = $methodDefinition;
            $mappings[$key]['before'] = implode(",",$beforeMiddleware);
            $mappings[$key]['after'] = implode(",",$afterMiddleware);
            $mappings[$key]['doc'] = 'not available';

        }

        return $mappings;
    }


}