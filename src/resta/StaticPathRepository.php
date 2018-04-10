<?php

namespace Resta;

class StaticPathRepository {

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
     * @return mixed
     */
    public function appStorage(){
        return StaticPathModel::getAppStorage();
    }

    /**
     * @return mixed
     */
    public function appResourche(){
        return StaticPathModel::getAppStorage().'/'.StaticPathModel::$resource;
    }


    /**
     * @return mixed
     */
    public function appKernel(){
        return $this->app().'\\'.StaticPathModel::$kernel;
    }

    /**
     * @return mixed
     */
    public function appCommand(){
        return $this->appKernel().'\\'.StaticPathModel::$command;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function app($app=null){

        $app=($app===null) ? $this->appDetector() : $app;
        return StaticPathModel::appPath().'/'.$app;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appVersion($app=null){

        return $this->app($app).'/'.Utils::getAppVersion(app);
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalException($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$optional.'/'.StaticPathModel::$optionalException;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalRepository($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$optional.'/'.StaticPathModel::$repository;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalJob($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$optional.'/'.StaticPathModel::$job;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalSource($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$optional.'/'.StaticPathModel::$sourcePath;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appOptionalWebservice($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$optional.'/'.StaticPathModel::$webservice;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appCall($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$controller;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMiddleware($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$middleware;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appModel($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$model;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appMigration($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$migration;
    }

    /**
     * @param null $app
     * @return mixed
     */
    public function appConfig($app=null){

        return $this->appVersion($app).'/'.StaticPathModel::$config;
    }

    /**
     * @return mixed|null
     */
    private function appDetector(){
        return (defined('app')) ? app : null;
    }

}