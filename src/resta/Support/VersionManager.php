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
        return static::$supportedVersions;
    }
}