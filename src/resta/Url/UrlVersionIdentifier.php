<?php

namespace Resta\Url;

use Resta\Support\Utils;

class UrlVersionIdentifier
{
    /**
     * list supported version numbers
     *
     * @var array
     */
    private static $supportedVersions = ['V1'];

    /**
     * get client version
     *
     * @return null|string
     */
    public static function clientVersion()
    {
        if(defined('version')){
            return version;
        }

        return null;
    }

    /**
     * check path url version
     *
     * @return bool
     */
    public static function checkPathUrlVersion()
    {
        return (file_exists(path()->version())) ? true : false;
    }

    /**
     * console version
     *
     * @return null
     */
    private static function consoleVersion()
    {
        return null;
    }

    /**
     * get application console version
     *
     * @return mixed
     */
    public static function getConsoleVersion()
    {
        if(Utils::isNamespaceExists(self::versionNamespace()) && method_exists(self::versionNamespace(),'consoleVersion')){
            return self::versionNamespace()::consoleVersion();
        }

        return null;
    }

    /**
     * @param array $versions
     * @return array
     */
    private static function getSupportedVersions($versions=array())
    {
        if(count($versions)){
            return $versions;
        }
        return self::$supportedVersions;
    }

    /**
     * get supported versions
     *
     * @param array $versions
     * @return mixed
     */
    public static function supportedVersions($versions=array())
    {
        return self::versionNamespace()::getSupportedVersions($versions);
    }

    /**
     * get version
     *
     * @return mixed|string|null
     */
    public static function version()
    {
        if(self::clientVersion()!==null){
            return self::clientVersion();
        }

        if(app()->console() && self::getConsoleVersion()!==null){
            return self::getConsoleVersion();
        }

        return 'V1';
    }

    /**
     * get version namespace
     *
     * @return UrlVersionIdentifier|string
     */
    public static function versionNamespace()
    {
        if(file_exists(app()->path()->kernel())){
            return app()->namespace()->kernel().'\Version';
        }

        return new static();

    }
}