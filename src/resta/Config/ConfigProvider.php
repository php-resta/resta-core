<?php

namespace Resta\Config;

use Resta\Support\Arr;
use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\Contracts\HandleContracts;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ConfigProviderContracts;
use Resta\Foundation\PathManager\StaticPathList;

class ConfigProvider extends ApplicationProvider implements ConfigProviderContracts,HandleContracts
{
    /**
     * config provider handle
     *
     * @return void
     */
    public function handle()
    {
        define('config',true);

        //set config container instance
        $this->app->instance('config',$this);

        //automatically add values ​​to the configuration files.
        $this->loadPreDefinedConfigurationFiles();

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
     * load pre defined configuration files
     *
     * @return void
     */
    private function loadPreDefinedConfigurationFiles()
    {
        $this->app->loadConfig(function(){

            return [
                'Kernel'    => path()->kernel().''.DIRECTORY_SEPARATOR.''.StaticPathList::$kernel.'.php',
                'Response'  => path()->storeConfigDir().''.DIRECTORY_SEPARATOR.'Response.php',

            ];
        });
    }

    /**
     * register configuration
     *
     * @param array $files
     * @return mixed|void
     */
    private function registerConfiguration($files=array())
    {
        // we are saving all paths in
        // the config directory of each application.
        foreach($files as $key=>$file){

            if(is_array($file)){

                $this->app->register('appConfig',Str::lower($key),[
                    'namespace' => null,
                    'file'      => null,
                    'data'      => $file
                ]);
            }

            elseif(file_exists($file)){

                $this->app->register('appConfig',Str::lower($key),[
                    'namespace' =>Utils::getNamespace($file),
                    'file'      =>$file
                ]);
            }
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
        $this->registerConfiguration($configFiles ?? $path);
    }
}