<?php

namespace Resta\Support;

class Dependencies
{
    /**
     * @var array $bootLoaders
     */
    protected static $bootLoaders = ['url','logger'];

    /**
     * load bootstrapper dependencies
     *
     * @param array $loaders
     */
    public static function bootLoader($loaders=array())
    {
        app()->loadIfNotExistBoot($loaders);
    }

    /**
     * get dependency boot loaders
     *
     * @return array
     */
    public static function getBootLoaders()
    {
        return self::$bootLoaders;
    }

    /**
     * load config general bootstrap as need
     *
     * @return void|mixed
     */
    public static function loadBootstrapperNeedsForException()
    {
        static::bootLoader(self::getBootLoaders());
    }
}