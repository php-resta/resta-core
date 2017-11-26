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

}