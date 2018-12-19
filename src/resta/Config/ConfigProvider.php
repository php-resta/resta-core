<?php

namespace Resta\Config;

use Resta\Utils;
use Resta\GlobalLoaders\Config as ConfigGlobalInstance;

class ConfigManager
{
    /**
     * @var $globalInstance ConfigGlobalInstance
     */
    protected static $globalInstance;

    /**
     * @param ConfigGlobalInstance $config
     */
    public function handle(ConfigGlobalInstance $config)
    {
        define('config',true);

        //global instance general property
        self::$globalInstance = $config;

        //set config values
        $this->setConfig();

        // Finally, we will set
        // the application's timezone and encoding based on the configuration
        if(config('app')!==null){
            date_default_timezone_set(config('app.timezone'));
            mb_internal_encoding('UTF-8');
        }
    }

    /**
     * @param null $path
     */
    public function setConfig($path=null)
    {
        //path variable for parameter
        $path = ($path === null) ? app()->path()->config() : $path;

        //We run a glob function for all of the config files,
        //where we pass namespace and paths to a kernel object and process them.
        $configFiles = Utils::glob($path);

        //The config object is a kernel object
        //that can be used to call all class and array files in the config directory of the project.
        self::$globalInstance->setConfig($configFiles);
    }
}