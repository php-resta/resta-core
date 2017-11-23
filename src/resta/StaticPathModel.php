<?php

namespace Resta;

/**
 * Class StaticPathModel
 * @package Resta
 */
class StaticPathModel {

    /**
     * @var string
     */
    public static $appDefine='src';

    /**
     * @var null
     */
    public static $appPath=null;


    /**
     * @method appPath
     * @return mixed
     */
    public static function appPath(){

        //get app path for application
        return self::$appPath=root.'/'.self::$appDefine.'/app';
    }
}