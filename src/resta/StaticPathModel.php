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
}