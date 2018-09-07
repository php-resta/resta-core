<?php

namespace Resta;

class StaticPathRepository {

    /**
     * @param null $app
     * @return mixed
     */
    public function app($app=null){

        $app=($app===null) ? $this->appDetector() : $app;
        return StaticPathModel::appPath().'/'.Str::slashToBackSlash($app);
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appBuilder($app=null){

        return app()->path()->model().'/'.StaticPathList::$builder;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appCall($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$controller;
    }

    /**
     * @return mixed
     */
    public function appCommand($app=null){
        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$command.'';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appConfig($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$config;
    }

    /**
     * @return mixed|null
     */
    private function appDetector(){
        return (defined('app')) ? app : null;
    }

    /**
     * @return mixed
     */
    public function appKernel(){

        $projectPrefix      = StaticPathModel::projectPrefix();
        $kernel     = $this->app().'/'.StaticPathList::$kernel;

        return StaticPathModel::projectPath($projectPrefix.'/',$kernel);

        return $this->app().'/'.StaticPathList::$kernel;
    }

    /**
     * @return mixed
     */
    public function appLanguage(){
        return StaticPathModel::getAppStorage().'/'.StaticPathList::$language;
    }

    /**
     * @return mixed
     */
    public function appLog(){
        return StaticPathModel::getAppStorage().'/'.StaticPathList::$log;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appLogger($app=null){

        return $this->appVersion($app).'/ServiceLogController';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMiddleware($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$middleware;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMigration($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$migration;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appModel($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$model;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalException($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$optionalException;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalEvents($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$events;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalJob($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$job;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalListeners($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$listeners;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalSubscribers($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$listeners.'/'.StaticPathList::$subscribers;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appRepository($app=null){

        $projectPrefix      = StaticPathModel::projectPrefix();
        $repositoryPath     = $this->app().'/'.StaticPathList::$repository;

        return StaticPathModel::projectPath($projectPrefix.'/',$repositoryPath);

    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalSource($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$sourcePath;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalWebservice($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$webservice;
    }

    /**
     * @return mixed
     */
    public function appResourche(){
        return StaticPathModel::getAppStorage().'/'.StaticPathList::$resource;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceAnnotations($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$serviceAnnotations;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceContainer($app=null){

        return $this->appVersion($app).'/ServiceContainerController';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceEventDispatcher($app=null){

        return $this->appVersion($app).'/ServiceEventDispatcherController';
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appServiceMiddleware($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$serviceMiddleware;
    }

    /**
     * @return mixed
     */
    public function appStorage(){
        return StaticPathModel::getAppStorage();
    }

    /**
     * @return mixed
     */
    public function appStubs(){
        return $this->appKernel().'/'.StaticPathList::$stub;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appVersion($app=null){

        if(defined('app')){
            return $this->app($app).'/'.Utils::getAppVersion(app);
        }

        return null;

    }

    /**
     * @return mixed
     */
    public function encrypterFile(){
        return StaticPathModel::getEncrypter();
    }

    /**
     * @return mixed
     */
    public function environmentFile(){
        return StaticPathModel::getEnvironmentFile();
    }

    /**
     * @param $name
     * @param $arg
     * @return mixed
     */
    public function __call($name,$arg){
        $appCall='app'.ucfirst($name);
        return $this->{$appCall}();
    }

}