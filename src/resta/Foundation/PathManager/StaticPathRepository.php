<?php

namespace Resta\Foundation\PathManager;

use http\Exception\InvalidArgumentException;
use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\Url\UrlVersionIdentifier;

class StaticPathRepository
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * StaticPathRepository constructor.
     */
    public function __construct()
    {
        $this->parameters = app()->get('parameters');
    }

    /**
     * @param null $app
     * @return string
     */
    public function app($app=null)
    {
        $app=($app===null) ? $this->appDetector() : $app;
        return StaticPathModel::appPath().''.DIRECTORY_SEPARATOR.''.Str::slashToBackSlash($app);
    }

    /**
     * @return mixed
     */
    public function autoloadNamespace()
    {
        return root.''.DIRECTORY_SEPARATOR.''.StaticPathList::$appDefine.''.DIRECTORY_SEPARATOR.''.strtolower(StaticPathList::$autoloadNamespace);
    }

    /**
     * @return string
     */
    public function appBuilder()
    {
        return path()->model().''.DIRECTORY_SEPARATOR.''.StaticPathList::$builder;
    }

    /**
     * @param null $app
     * @return string
     */
    public function appCall($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$controller;
    }

    /**
     * @param null $app
     * @return string
     */
    public function appCommand()
    {
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$command.'';
    }

    /**
     * @param null $app
     * @return string
     */
    public function appConfig($app=null)
    {
        if(isset($this->parameters['paths']['config']) && file_exists($this->parameters['paths']['config'])){
            return $this->parameters['paths']['config'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$config;
        }

        die('config path invalid for parameters file ('.($this->parameters['paths']['config'] ?? null).')');
    }

    /**
     * @param null $app
     * @return string
     */
    public function appHelpers($app=null)
    {
        if(isset($this->parameters['paths']['helper'])){
            return $this->parameters['paths']['helper'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$helpers;
        }

        die('helper path is not valid for parameters file');
    }

    /**
     * @return string
     */
    public function appTests()
    {
        return $this->app().''.DIRECTORY_SEPARATOR.''.StaticPathList::$test;
    }

    /**
     * @return string
     */
    public function appWorkers()
    {
        return  $this->appVersion(null).''.DIRECTORY_SEPARATOR.''.StaticPathList::$workers;
    }

    /**
     * @return string
     */
    public function appSchedule()
    {
        return  $this->appVersion(null).''.DIRECTORY_SEPARATOR.''.StaticPathList::$schedule;
    }

    /**
     * @return null|string
     */
    private function appDetector()
    {
        return (defined('app')) ? app : null;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appFactory($app=null)
    {
        if(isset($this->parameters['paths']['factory'])){
            return $this->parameters['paths']['factory'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$factory;
        }

        die('factory path is not valid for parameters file');
    }

    /**
     * @return mixed|string
     */
    public function appKernel()
    {
        if(isset($this->parameters['paths']['kernel'])){
            return $this->parameters['paths']['kernel'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$kernel;
        }

        die('kernel path is not valid for parameters file');
    }

    /**
     * @return mixed|string
     */
    public function appProvider()
    {
        $kernel     = $this->appKernel().''.DIRECTORY_SEPARATOR.''.StaticPathList::$provider;

        return $kernel;
    }

    /**
     * @return string
     */
    public function appLanguage()
    {
        return self::appStorage().''.DIRECTORY_SEPARATOR.''.StaticPathList::$language;
    }

    /**
     * @return string
     */
    public function appLog()
    {
        return self::appStorage().''.DIRECTORY_SEPARATOR.''.StaticPathList::$log;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appLogger($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.'ServiceLogManager';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMiddleware($app=null)
    {
        if(isset($this->parameters['paths']['middleware'])){
            return $this->parameters['paths']['middleware'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$middleware;
        }

        die('middleware path in parameters file is not valid');
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMigration($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$migration;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appModel($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$model;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appException($app=null)
    {
        if(isset($this->parameters['paths']['exception'])){
            return $this->parameters['paths']['exception'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$exception;
        }

        die('exception path in parameters file is not valid');
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalEvents($app=null)
    {
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$events;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalJob($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$optional.''.DIRECTORY_SEPARATOR.''.StaticPathList::$job;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalListeners($app=null)
    {
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$listeners;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalSubscribers($app=null)
    {
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$listeners.''.DIRECTORY_SEPARATOR.''.StaticPathList::$subscribers;
    }

    /**
     * @return mixed
     */
    public function appRequest()
    {
        if(isset($this->parameters['paths']['client'])){
            return $this->parameters['paths']['client'].''.DIRECTORY_SEPARATOR.'Client';
        }

        die('client path is not valid for parameters file');
    }

    /**
     * @return mixed
     */
    public function appRepository()
    {
        if(isset($this->parameters['paths']['repository'])){
            return $this->parameters['paths']['repository'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$repository;
        }

        die('repository path is not valid for parameters file');
    }

    /**
     * @return mixed
     */
    public function appRoute()
    {
        if(isset($this->parameters['paths']['route'])){
            return $this->parameters['paths']['route'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$route;
        }

        die('route file is not valid for parameters file');
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalSource($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$optional.''.DIRECTORY_SEPARATOR.''.StaticPathList::$sourcePath;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalWebservice($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$optional.''.DIRECTORY_SEPARATOR.''.StaticPathList::$webservice;
    }

    /**
     * @return mixed
     */
    public function appResourche()
    {
        return self::appStorage().''.DIRECTORY_SEPARATOR.''.StaticPathList::$resource;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceAnnotations($app=null)
    {
        if(isset($this->parameters['paths']['serviceAnnotation'])){
            return $this->parameters['paths']['serviceAnnotation'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$serviceAnnotations.'.php';
        }

        die('service annotation file is not valid for parameters file');
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceContainer($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.'ServiceContainerController';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceEventDispatcher($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.'ServiceEventDispatcherManager';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceMiddleware($app=null)
    {
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$serviceMiddleware;
    }

    /**
     * @return mixed
     */
    public function appStorage()
    {
        $extension  = DIRECTORY_SEPARATOR.''.StaticPathList::$storage;

        if(isset($this->parameters['paths']['storage']) && file_exists($this->parameters['paths']['storage'])){
            return $this->parameters['paths']['storage'].''.$extension;
        }

        die('storage path invalid for parameters file ('.($this->parameters['paths']['storage'] ?? null).')');
    }

    /**
     * @return mixed
     */
    public function appStubs()
    {
        return $this->appKernel().''.DIRECTORY_SEPARATOR.''.StaticPathList::$stub;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appVersion($app=null)
    {
        if(defined('app')){

            $prefixGroup = Str::slashToBackSlash(StaticPathList::$projectPrefixGroup);

            $app = $this->app($app).''.DIRECTORY_SEPARATOR.''.$prefixGroup.''.DIRECTORY_SEPARATOR.''.UrlVersionIdentifier::version();

            return $app;

        }
        return null;
    }

    /**
     * @return string
     */
    public function bootDir()
    {
        //get boot directory for application
        return root.''.DIRECTORY_SEPARATOR.''.StaticPathList::$appDefine.''.DIRECTORY_SEPARATOR.''.strtolower(StaticPathList::$boot);
    }

    /**
     * @return string
     */
    public function storeConfigDir()
    {
        //get store config directory for application
        return $this->storeDir().'/Config';
    }

    /**
     * @return string
     */
    public function storeDir()
    {
        //get store directory for application
        return root.''.DIRECTORY_SEPARATOR.''.StaticPathList::$appDefine.''.DIRECTORY_SEPARATOR.''.strtolower(StaticPathList::$store);
    }

    /**
     * @param null $controller
     * @param bool $bool
     * @return mixed
     */
    public function controller($controller=null,$bool=false)
    {
        if(isset($this->parameters['paths']['controller'])){
            return $this->parameters['paths']['controller'].''.DIRECTORY_SEPARATOR.''.StaticPathList::$controller;
        }

        die('controller path is not valid for parameters file');
    }

    /**
     * @return mixed
     */
    public function encrypterFile()
    {
        return StaticPathModel::getEncrypter();
    }

    /**
     * @return mixed
     */
    public function environmentFile()
    {
        if(isset($this->parameters['paths']['environmentFile'])){
            return $this->parameters['paths']['environmentFile'].''.DIRECTORY_SEPARATOR.'env.yaml';
        }

        die('environment file path is not valid for parameters file');
    }

    /**
     * @param $name
     * @param $arg
     * @return mixed
     */
    public function __call($name,$arg)
    {
        $appCall='app'.ucfirst($name);
        return $this->{$appCall}();
    }

}