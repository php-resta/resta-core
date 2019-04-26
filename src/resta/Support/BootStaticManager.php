<?php

namespace Resta\Support;

class BootStaticManager
{
    /**
     * @var $requestPath
     */
    private static $requestPath;

    /**
     * set application request path
     *
     * @param null $path
     */
    public static function setPath($path=null)
    {
        if(!is_null($path)){
            self::$requestPath = $path;
        }
    }

    /**
     * get request path
     *
     * @return mixed
     */
    public static function getRequestPath()
    {
        return self::$requestPath;
    }
}