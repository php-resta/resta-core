<?php

namespace Resta;

/**
 * Class StaticPathModel
 * @package Resta
 */
class StaticPathModel extends StaticPathList {

    /**
     * @method appPath
     * @return mixed
     */
    public static function appPath(){

        //get app path for application
        return self::$appPath=root.'/'.self::$appDefine.'/app';
    }

    /**
     * @method appVersionRoot
     * @return string
     */
    public static function appVersionRoot(){

        return self::$autoloadNamespace.'\\'.app.'\\'.Utils::getAppVersion(app).'';
    }

    public static function endpointPath(){

        return self::appPath().'/'.app.'/'.Utils::getAppVersion(app).'/'.self::$controller.'/'.endpoint;
    }

    /**
     * @method getServiceConf
     * @return string
     */
    public static function getServiceConf(){

        return self::endpointPath().'/ServiceConf.php';
    }

    /**
     * @method getServiceDummy
     * @return string
     */
    public static function getServiceDummy(){

        return self::endpointPath().'/Dummy.yaml';
    }

    /**
     * @var $namespace
     * @method appConfig
     * @return string
     */
    public static function appConfig($namespace=false){

        //get app config path for application
        if(false===$namespace){
            return self::appPath().'/'.app.'/'.Utils::getAppVersion(app).'/'.self::$config;
        }
        return self::$autoloadNamespace.'\\'.app.'\\'.Utils::getAppVersion(app).'\\'.self::$config;
    }

    /**
     * @return mixed
     */
    public static function httpHeaders(){

        //get http header
        /*** @var $httpHeaders \Store\Config\HttpHeaders */
        $httpHeaders=self::$store.'\Config\HttpHeaders';

        //return http headers
        return $httpHeaders::$httpHeaders;
    }

    public static function appBase(){

        return self::appVersionRoot().'\\ServiceBaseController';
    }

}