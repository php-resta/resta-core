<?php

namespace Resta\Foundation\PathManager;

use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\Url\UrlVersionIdentifier;

class StaticPathRepository
{
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
        if(isset(core()->paths['config'])){
            return core()->paths['config'];
        }
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$config;
    }

    /**
     * @param null $app
     * @return string
     */
    public function appHelpers($app=null)
    {
        if(isset(core()->paths['config'])){
            return core()->paths['config'];
        }
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$helpers;
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
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$factory;
    }

    /**
     * @return mixed|string
     */
    public function appKernel()
    {
        $kernel     = $this->app().''.DIRECTORY_SEPARATOR.''.StaticPathList::$kernel;

        return $kernel;
    }

    /**
     * @return mixed|string
     */
    public function appProvider()
    {
        $kernel     = $this->app().''.DIRECTORY_SEPARATOR.''.StaticPathList::$kernel.''.DIRECTORY_SEPARATOR.''.StaticPathList::$provider;

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
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$middleware;
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
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$exception;
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
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$request;
    }

    /**
     * @return mixed
     */
    public function appRepository()
    {
        $repository     = $this->app().''.DIRECTORY_SEPARATOR.''.StaticPathList::$repository;

        return $repository;
    }

    /**
     * @return mixed
     */
    public function appRoute()
    {
        return $this->appVersion().''.DIRECTORY_SEPARATOR.''.StaticPathList::$route;
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
        return $this->appVersion($app).''.DIRECTORY_SEPARATOR.''.StaticPathList::$serviceAnnotations;
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
        $storage = $this->app().''.DIRECTORY_SEPARATOR.''.StaticPathList::$storage;

        return $storage;
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
        $namespaceController = ($controller===null) ? app()->namespace()->controller()
            : app()->namespace()->controller($controller,true);

        if($bool){
            $namespaceControllerExplode = explode("\\",$namespaceController);
            array_pop($namespaceControllerExplode);

            $namespaceController = implode("\\",$namespaceControllerExplode);
        }

        return Utils::getPathFromNamespace($namespaceController,false);
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
        return StaticPathModel::getEnvironmentFile();
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