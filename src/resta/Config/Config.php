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
     * Config constructor.
     */
    public function __construct()
    {
        // we create a singleton object for the config process class.
        if(self::$config!==null && self::$configProcessInstance===null){
            self::$configProcessInstance=new ConfigProcess();
        }
    }

    /**
     * @return mixed|null
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
        // check static singleton object
        // then set as singleton with new self
        if(self::$instance===null){
            self::$instance=new self();
        }

        //static single object set config
        return self::$instance->setConfig($config);
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function set($data=array())
    {
        $configPath     = path()->config();
        $configArray    = current(Str::stringToArray(self::$config));
        $setConfigPath  = $configPath.''.DIRECTORY_SEPARATOR.''.ucfirst($configArray).'.php';

        /**
         * @var $fileProcess FileProcess
         */
        $fileProcess = app()->makeBind(FileProcess::class);
        $getConfig   = config($configArray);

        // if the config contents come to null,
        // we assign an empty array value to the variable.
        $getConfigWrap = Arr::wrap($getConfig);

        foreach ($data as $value){
            
            // we check the config value not to be rewritten.
            if(!in_array($value,$getConfigWrap)){
                $setData = '<?php return '.var_export(array_merge($getConfigWrap,$data), true).';';
                $fileProcess->dumpFile($setConfigPath,$setData);
            }
        }
    }

    /**
     * @param $config
     * @return Config
     */
    private function setConfig($config)
    {
        self::$config = $config;

        return new self();
    }
}