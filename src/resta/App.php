<?php

namespace Resta;

use Store\Services\HttpSession as Session;
use Store\Services\Redis as Redis;
use Store\Services\AppCollection as Collection;
use Store\Services\DateCollection as Date;

/**
 * Class App
 * @package Resta
 */
class App {

    /**
     * @var array
     */
    protected static $instance=[];

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

        //if $name starts with $needles for repository
        if(Str::endsWith($service,'Repository')){
            return self::repository($service);
        }

        //if $name starts with $needles for model
        if(Str::endsWith($service,'Builder')){
            return self::Builder(ucfirst($service));
        }

        return self::$service($arg);
    }

    /**
     * @param $arg
     * @return Session
     */
    private static function session($arg){

        return new Session();
    }

    /**
     * @param $arg
     * @return Session
     */
    private static function date($arg){

        if(property_exists($class=pos($arg),'app')){
            return $class->makeBind(Date::class);
        }

        return null;
    }

    /**
     * @param $service
     * @return mixed
     */
    private static function repository($service){

        //I can get the repository name from the magic method as a salt repository,
        //after which we will edit it as an adapter namespace.
        $repositoryName=ucfirst(preg_replace('@Repository@is','',$service));

        //If we then configure the name of the simple repository to be an adapter
        //then we will give the user an example of the adapter class in each repository call.
        $repositoryAdapterName  = $repositoryName.'Adapter';
        $repositoryNamespace    = StaticPathModel::appRepository().'\\'.$repositoryName.'\\'.$repositoryAdapterName;

        //and eventually we conclude the adapter class of the repository package as an instance.
        return app()->makeBind($repositoryNamespace)->adapter();
    }

    /**
     * @param $service
     * @return mixed
     */
    private static function builder($service){

        //We are making a namespace assignment for the builder.
        $builder=StaticPathModel::appBuilder().'\\'.$service;

        //We are getting builder instance.
        return app()->makeBind($builder);
    }

    /**
     * @param $arg
     * @return \Predis\Client
     */
    private static function redis($arg){

        if(!isset(self::$instance['redis'])){

            self::$instance['redis']=(new Redis())->client();
            return self::$instance['redis'];

        }

        return self::$instance['redis'];

    }


    /**
     * @param $arg
     * @return Collection
     */
    private static function collection($arg){

        return (new Collection());
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