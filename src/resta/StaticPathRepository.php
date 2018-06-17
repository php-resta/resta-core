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
    public function appCall($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$controller;
    }

    /**
     * @return mixed
     */
    public function appCommand(){
        return $this->appKernel().'\\'.StaticPathList::$command;
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
        return $this->app().'/'.StaticPathList::$kernel;
    }

    /**
     * @return mixed
     */
    public function appLanguage(){
        return StaticPathModel::getAppStorage().'/'.StaticPathList::$language;
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
    public function appOptionalJob($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$job;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalRepository($app=null){

        return $this->appVersion($app).'/'.StaticPathList::$optional.'/'.StaticPathList::$repository;
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
    public function appServiceContainer($app=null){

        return $this->appVersion($app).'/ServiceContainerController';
    }

    /**
     * @return mixed
     */
    public function appStorage(){
        return StaticPathModel::getAppStorage();
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appVersion($app=null){

        return $this->app($app).'/'.Utils::getAppVersion(app);
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