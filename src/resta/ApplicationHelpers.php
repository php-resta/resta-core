<?php

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
        die();
    }
}

if (!function_exists('environment')) {
    function environment()
    {
        return \Resta\Environment\EnvironmentConfiguration::environment(
            func_get_args(),app()->singleton()->var
        );
    }
}

if (!function_exists('appInstance')) {

    /**
     * @return \Resta\ApplicationProvider
     */
    function appInstance()
    {
        return \application::getAppInstance();
    }
}

if (!function_exists('app')) {

    /**
     * @return \Resta\Contracts\ApplicationContracts|\Resta\Contracts\ApplicationHelpersContracts|\Resta\Contracts\ContainerContracts
     */
    function app()
    {
        return appInstance()->app;
    }
}

if (!function_exists('request')) {

    /**
     * @return \Store\Services\RequestService|\Symfony\Component\HttpFoundation\Request
     */
    function request()
    {
        return appInstance()->request();
    }
}


if (!function_exists('post')) {

    /**
     * @param null $param
     * @return mixed
     */
    function post($param=null)
    {
        return appInstance()->post($param);
    }
}



if (!function_exists('get')) {

    /**
     * @param null $param
     * @return mixed
     */
    function get($param=null)
    {
        return appInstance()->get($param);
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

if (!function_exists('config')) {

    /**
     * @param $config null
     * @return string
     */
    function config($config=null)
    {
        return app()->singleton()->appClass->configLoaders($config);
    }
}

if (!function_exists('resolve')) {

    /**
     * @param $class
     * @param array $bind
     * @return mixed
     */
    function resolve($class,$bind=array())
    {
        return appInstance()->makeBind($class,$bind);
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

if (!function_exists('route')) {

    /**
     * @param $key
     * @return mixed
     */
    function route($key=null)
    {
        return app()->singleton()->appClass->route($key);
    }
}

if (!function_exists('exception')) {

    /**
     * @return \Store\Exception\ExceptionContracts
     */
    function exception()
    {
        $exceptionManager=Store\Exception\ExceptionManager::class;
        return new $exceptionManager;
    }
}

if (!function_exists('logger')) {

    /**
     * @param $output
     * @param $file
     * @param string $type
     * @return mixed
     */
    function logger($output,$file,$type="access")
    {
        return app()->singleton()->appClass->logger($output,$file,$type);
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

if (!function_exists('call')) {


    /**
     * @param $call
     * @return mixed
     */
    function call($call)
    {
        return app()->namespace()->call($call);
    }
}