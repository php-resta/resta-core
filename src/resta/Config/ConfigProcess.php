<?php

namespace Resta\Config;

use Resta\Utils;

class ConfigProcess {

    /**
     * @var $config
     */
    protected $config;

    /**
     * ConfigProcess constructor.
     * @param $config null
     */
    public function __construct($config=null) {
        $this->config=$config;
    }

    /**
     * @return mixed
     */
    protected function config(){

        //We are getting the config data from the kernel object..
        $kernelConfig=app()->singleton()->appConfig;

        // if the config variable is not sent,
        // we print the kernel config data directly.
        if(null===$this->config) return $kernelConfig;

        // we are starting a array of
        // point-based logical processes for config data processing.
        $this->config=$this->configExplode();

        //If the config object exists in the kernel, start the process.
        if(isset($kernelConfig[$config=current($this->config)])){

            //If the config data is a class instance, we get it as an object.
            if(Utils::isNamespaceExists($configFile=$kernelConfig[$config]['namespace'])){
                $configData=Utils::makeBind($configFile)->handle();
            }

            //If the config data is just an array.
            if(!isset($configData) && file_exists($configFile=$kernelConfig[$config]['file'])){
                $pureConfig=require($configFile);
                $configData=$pureConfig;
            }

            //we process and rotate on point basis.
            return $this->configProcessResult($configData);

        }

        //if the submitted config does not match any value.
        throw new \InvalidArgumentException('The requested config is not available');
    }

    /**
     * @param $confi
     */
    protected function configProcessResult($config){

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
     * @return mixed
     */
    public function get(){
        return $this->config();
    }

    /**
     * @param string $explode
     * @return array
     */
    protected function configExplode($explode="."){
        return explode($explode,$this->config);
    }

}