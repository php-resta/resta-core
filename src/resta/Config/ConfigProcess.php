<?php

namespace Resta\Config;

use Resta\Utils;

class ConfigProcess
{
    /**
     * @var $config
     */
    protected $config;

    /**
     * ConfigProcess constructor.
     * @param null $config
     */
    public function __construct($config=null)
    {
        $this->config=$config;
    }

    /**
     * @return mixed|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function config()
    {
        $kernelConfig = [];

        //we are getting the config data from the kernel object..
        if(isset(app()->singleton()->appConfig)){
            $kernelConfig=app()->singleton()->appConfig;
        }

        // if the config variable is not sent,
        // we print the kernel config data directly.
        if(null===$this->config) return (is_array($kernelConfig) && count($kernelConfig)) ? $kernelConfig : null;

        // we are starting a array of
        // point-based logical processes for config data processing.
        $this->config=$this->configExplode();

        //if the config object exists in the kernel, start the process.
        if(isset($kernelConfig[$config=current($this->config)])){

            //get config data
            $configData=$this->getConfigData($kernelConfig,$config);

            //we process and rotate on point basis.
            return $this->configProcessResult($configData);
        }

        return null;
    }

    /**
     * @param string $explode
     * @return array
     */
    protected function configExplode($explode=".")
    {
        return explode($explode,$this->config);
    }

    /**
     * @param $config
     * @return mixed
     */
    protected function configProcessResult($config)
    {
        //config data if dotted.
        if(count($this->config)){

            array_shift($this->config);
            $configRecursive=$config;

            //we apply the dotted-knit config dataset as nested.
            foreach ($this->config as $key=>$value){
                $configRecursive=$configRecursive[$value];
            }
        }

        // if the config data is not dotted,normal data is printed
        // if not, the nested data is printed.
        return (isset($configRecursive)) ? $configRecursive : $config;
    }

    /**
     * @return mixed|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get()
    {
        return $this->config();
    }

    /**
     * @param $kernelConfig
     * @param $config
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function getConfigData($kernelConfig,$config)
    {
        //if the config data is a class instance, we get it as an object.
        if(Utils::isNamespaceExists($configFile=$kernelConfig[$config]['namespace'])){
            $configData=Utils::makeBind($configFile)->handle();
        }

        //if the config data is just an array.
        if(!isset($configData) && file_exists($configFile=$kernelConfig[$config]['file'])){
            $pureConfig=require($configFile);
            $configData=$pureConfig;
        }

        return $configData;
    }
}