<?php

namespace Resta\Config;

use Resta\Support\Arr;
use Resta\Support\Str;
use Resta\FileProcess;
use Resta\Support\Utils;
use Resta\Contracts\AccessorContracts;

class Config implements AccessorContracts
{
    /**
     * @var null $config
     */
    private static $config = null;

    /**
     * @var null $configProcessInstance
     */
    private static $configProcessInstance = null;

    /**
     * @var null $instance
     */
    private static $instance = null;

    /**
     * @return mixed|null
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get()
    {
        // The config process class should not be null.
        if(self::$configProcessInstance!==null){

            //get config variables
            $config = self::$config;

            // offset config variables to config process class
            self::$configProcessInstance->offsetSet('config',$config);

            //get config variable from config process class
            return self::$configProcessInstance->get();
        }

        return null;
    }

    /**
     * @param null $config
     * @return Config
     */
    public static function make($config=null)
    {
        self::$config = $config;
        self::$configProcessInstance = app()->makeBind(ConfigProcess::class);

        //static single object set config
        return new self();
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function set($data=array())
    {
        // receive the necessary config settings.
        $configPath     = path()->config();
        $configArray    = current(Str::stringToArray(self::$config));
        $setConfigPath  = $configPath.''.DIRECTORY_SEPARATOR.''.ucfirst($configArray).'.php';
        $getConfigWrap  = Arr::wrap(config($configArray));

        foreach ($data as $value){
            
            // we check the config value not to be rewritten.
            if(!in_array($value,$getConfigWrap)){
                $setData = '<?php return '.var_export(array_merge($getConfigWrap,$data), true).';';
                app()->makeBind(FileProcess::class)->dumpFile($setConfigPath,$setData);
            }
        }
    }
}