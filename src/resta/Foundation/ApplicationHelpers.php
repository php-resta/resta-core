<?php

use Faker\Factory;
use Faker\Generator;
use Resta\Router\Route;
use Resta\Support\Utils;
use Resta\Support\Filesystem;
use Resta\Logger\LoggerHandler;
use Store\Services\RequestService;
use Resta\Exception\ExceptionManager;
use Resta\Support\HigherOrderTapProxy;
use Resta\Response\ResponseOutManager;
use Resta\EventDispatcher\EventManager;
use Resta\Contracts\ExceptionContracts;
use Resta\Contracts\StaticPathContracts;
use Resta\Contracts\ApplicationContracts;
use Resta\Authenticate\AuthenticateContract;
use Resta\Authenticate\AuthenticateProvider;
use Symfony\Component\HttpFoundation\Request;
use Resta\Foundation\PathManager\StaticPathList;

if (!function_exists('app')) {

    /**
     * @return ApplicationContracts
     */
    function app()
    {
        return appInstance();
    }
}

if (!function_exists('appInstance')) {

    /**
     * @return mixed
     */
    function appInstance()
    {
        return \application::getAppInstance();
    }
}

if (!function_exists('applicationKey')) {

    /**
     * @return string
     */
    function applicationKey()
    {
        if(property_exists($kernel=app()->kernel(),'applicationKey')){
            return $kernel->applicationKey;
        }
        return null;

    }
}

if (!function_exists('auth')) {
    /**
     * @return AuthenticateContract
     */
    function auth()
    {
        return app()->resolve(AuthenticateProvider::class);
    }
}

if (!function_exists('bind')) {

    function bind()
    {
        return (object)app()['serviceContainer'];
    }
}

if (!function_exists('bundleName')) {

    /**
     * @return null|string
     */
    function bundleName()
    {
        if(defined('endpoint')){

            return endpoint.''.StaticPathList::$controllerBundleName;
        }
        return null;
    }
}

if (!function_exists('config')) {

    /**
     * @param null $config
     * @param null $default
     * @return mixed|null
     */
    function config($config=null,$default=null)
    {
        $configResult = app()->config($config);

        if($configResult === null && $default!==null){
            return $default;
        }

        return $configResult;
    }
}

if (!function_exists('container')) {

    /**
     * @param $class
     * @param $bind array
     * @return mixed
     */
    function container($class,$bind=array())
    {
        return app()->singleton()->appClass->container(appInstance(),$class,$bind);
    }
}

if (!function_exists('core')) {

    /**
     * @return mixed
     */
    function core()
    {
        return app()->singleton();
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
        die();
    }
}

if (!function_exists('files')) {

    /**
     * @return Filesystem
     */
    function files() : Filesystem
    {
        return app()->resolve(Filesystem::class);
    }
}

if (!function_exists('environment')) {
    function environment()
    {
        return app()->environment(func_get_args());
    }
}

if (!function_exists('event')) {

    /**
     * @return EventManager
     */
    function event()
    {
        return app()->singleton()->bindings['eventDispatcher'];
    }
}

if (!function_exists('exception')) {

    /**
     * @param null $name
     * @param array $params
     * @return ExceptionContracts
     */
    function exception($name=null,$params=array())
    {
        if(isset($params['key'])){
            app()->register('errorInput',$params['key']);
        }

        $exceptionManager=ExceptionManager::class;
        return app()->resolve($exceptionManager,['name'=>$name,'params'=>$params]);
    }
}

if (!function_exists('faker')) {

    /**
     * @param null $locale
     * @return Generator
     */
    function faker($locale=null)
    {
        if($locale===null){
            $faker=Factory::create();
        }
        else{
            $faker=Factory::create($locale);
        }

        return $faker;
    }
}

if (!function_exists('fingerPrint')) {

    function fingerPrint()
    {
        return md5(sha1(implode("|",[
            request()->getClientIp(),applicationKey()
        ])));
    }
}

if (!function_exists('fullUrl')) {

    function fullUrl()
    {
        return request()->getUri();
    }
}

if (!function_exists('get')) {

    /**
     * @param null $param
     * @param null $default
     * @return null
     */
    function get($param=null,$default=null)
    {
        //symfony request query object
        $get=core()->get;

        return ($param===null) ? $get : (isset($get[$param]) ? $get[$param] : $default);
    }
}

if (!function_exists('headers')) {

    /**
     * @param null $param
     * @param null $default
     * @return array|string
     */
    function headers($param=null,$default=null)
    {
        $list=[];

        //We only get the objects in the list name to match the header objects
        //that come with the request path to the objects sent by the client
        foreach (request()->headers->all() as $key=>$value) {
            $list[$key]=$value;
        }

        //return header list
        return ($param===null) ? $list : (isset($list[$param]) ? $list[$param][0] : $default);
    }
}

if (!function_exists('httpMethod')) {

    /**
     * @return string
     */
    function httpMethod()
    {
        return strtolower(core()->httpMethod);
    }
}

if (!function_exists('logger')) {

    /**
     * @param $file null
     * @return LoggerHandler
     */
    function logger($file=null)
    {
        return app()->resolve(LoggerHandler::class,['file'=>$file]);
    }
}

if (!function_exists('request')) {

    /**
     * @return RequestService|Request
     */
    function request()
    {
        return core()->request;
    }
}

if (!function_exists('response')) {

    /**
     * @return ResponseOutManager
     */
    function response()
    {
        $object=debug_backtrace()[1]['object'];
        return new ResponseOutManager($object);
    }
}

if (!function_exists('resolve')) {

    /**
     * @param $class
     * @param array $bind
     * @return mixed|null
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    function resolve($class,$bind=array())
    {
        return app()->resolve($class,$bind);
    }
}

if (!function_exists('policy')) {

    /**
     * @return mixed
     */
    function policy()
    {
        $policyPath = implode('/',[
            Route::getRouteControllerNamespace(),
            'Policy',
            Route::getRouteControllerClass().'Policy'
        ]);

        $policyNamespace = Utils::getNamespace($policyPath);

        if(Utils::isNamespaceExists($policyNamespace)){
            return app()->resolve(Utils::getNamespace($policyPath));
        }

        return stdClass::class;

    }
}

if (!function_exists('route')) {

    /**
     * @param $key
     * @return mixed
     */
    function route($key=null)
    {
        if(is_null($key)){
            return array_map(function($route){
                return strtolower($route);
            },app()->singleton()->appClass->route($key));
        }

        return app()->singleton()->appClass->route($key);

    }
}

if (!function_exists('path')) {

    /**
     * @return StaticPathContracts
     */
    function path()
    {
        return app()->path();
    }
}

if (!function_exists('post')) {

    /**
     * @param null $param
     * @param null $default
     * @return mixed
     */
    function post($param=null,$default=null)
    {
        //symfony request query object
        $post=core()->post;

        return ($param===null) ? $post : (isset($post[$param]) ? $post[$param] : $default);
    }
}

if (!function_exists('tap')) {

    /**
     * @param $value
     * @param $callback
     * @return mixed
     */
    function tap($value, $callback)
    {
        if (!is_callable($callback)) {
            return new HigherOrderTapProxy($value);
        }

        $callback($value);
        return $value;
    }
}

if (!function_exists('trans')) {

    /**
     * @param $lang
     * @param array $select
     * @return mixed
     */
    function trans($lang,$select=array())
    {
        return app()->singleton()->appClass->translator($lang,$select);
    }
}

if (!function_exists('entities')) {

    /**
     * @param null|string $table
     * @return void|array
     */
    function entities($table=null)
    {
        if(!is_null($table) && app()->has('entities')){
            return app()->get('entities')($table);
        }

        exception()->runtime('entities container not found');
    }
}

if (!function_exists('serviceJson')) {

    /**
     * @return string
     */
    function containerCacheFile()
    {
        $file = app()->path()->kernel().''.DIRECTORY_SEPARATOR.'service.json';

        if(file_exists($file)){
            return $file;
        }

        return null;
    }
}