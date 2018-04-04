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
     * @return mixed|null
     */
    private function appDetector(){
        return (defined('app')) ? app : null;
    }

}