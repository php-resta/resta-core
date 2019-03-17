<?php

namespace Resta\Support;

abstract class VersionManager
{
    /**
     * @var array
     */
    protected static $supportedVersions = [];

    /**
     * get supported versions
     *
     * @return mixed
     */
    public static function getSupportedVersions()
    {
        if(self::checkSupportedVersions()){
            static::$supportedVersions = array_merge(static::$supportedVersions,static::supportedVersions());
        }
        return static::$supportedVersions;
    }

    /**
     * check for merging supported versions
     *
     * @return bool
     */
    private static function checkSupportedVersions()
    {
        return method_exists(static::class,'supportedVersions')
            && is_array(static::supportedVersions());
    }
}