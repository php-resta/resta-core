<?php

namespace Resta\Config;

use Resta\Support\Arr;
use Resta\Support\Str;
use Resta\Support\Macro;
use Resta\Support\FileProcess;
use Resta\Contracts\AccessorContracts;

class Config implements AccessorContracts
{
    /**
     * @var null|string $config
     */
    private static $config = null;

    /**
     * @var null|object $configProcessInstance
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
     * get config data
     *
     * @return mixed
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * get config instance
     *
     * @return Config
     */
    public static function getInstance()
    {
        return new self();
    }

    /**
     * get config macro class from application kernel
     *
     * @param null|string $config
     * @return Config
     */
    public static function macro($config=null)
    {
        /** @var Macro $macro */
        $macro = app()->get('macro');
        $macro->setValues($config);

        return self::make('kernel.macros.config')->get();
    }

    /**
     * @param null $config
     * @return Config
     */
    public static function make($config=null)
    {
        self::$config = $config;
        self::$configProcessInstance = app()->resolve(ConfigProcess::class);

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
                return app()->resolve(FileProcess::class)->dumpFile($setConfigPath,$setData);
            }
        }
    }
}