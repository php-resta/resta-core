<?php

namespace Resta;

use function DI\object;
use Store\Services\HttpSession as Session;
use Store\Services\Redis as Redis;

/**
 * Class App
 * @package Resta
 */
class App {

    /**
     * @return mixed
     */
    public static function getAppInstance(){

        //we save an instance for the entire application
        //and add it to the helper file to be accessed from anywhere in the application.
        return unserialize(base64_decode(appInstance));
    }

    /**
     * @param $service
     * @param $arg
     */
    public static function annotationsLoaders($service,$arg){

        return self::$service();
    }

    /**
     * @return Session
     */
    private static function session(){

        return new Session();
    }

    /**
     * @return \Predis\Client
     */
    private static function redis(){

        return new Redis();
    }

    /**
     * @param null $config
     * @return string
     */
    public function configLoaders($config=null){

        $border=new self;

        $kernelConfig=$border->getAppInstance()->singleton()->appConfig;

        if(null===$config){
            return $kernelConfig;
        }

        if(isset($kernelConfig[$config])){

            if(Utils::isNamespaceExists($configFile=$kernelConfig[$config]['namespace'])){
                return (object)Utils::makeBind($configFile)->handle();
            }

            if(file_exists($configFile=$kernelConfig[$config]['file'])){
                $pureConfig=require($configFile);
                return (object)$pureConfig;
            }
        }

        throw new \InvalidArgumentException('The requested config is not available');
    }

}