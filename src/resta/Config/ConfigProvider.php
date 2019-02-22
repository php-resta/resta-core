<?php

namespace Resta\Config;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ConfigProviderContracts;

class ConfigProvider extends ApplicationProvider implements ConfigProviderContracts
{
    /**
     * @var $globalInstance ConfigGlobalInstance
     */
    protected static $globalInstance;

    /**
     * @param ConfigKernelAssigner $config
     */
    public function handle(ConfigKernelAssigner $config)
    {
        define('config',true);

        //set config container instance
        $this->app->instance('config',$this);

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
        if(!is_array($path)){

            // path variable for parameter
            // we run a glob function for all of the config files,
            // where we pass namespace and paths to a kernel object and process them.
            $path = ($path === null) ? path()->config() : $path;
            $configFiles = Utils::glob($path);

        }

        //The config object is a kernel object
        //that can be used to call all class and array files in the config directory of the project.
        self::$globalInstance->setConfig($configFiles ?? $path);
    }
}