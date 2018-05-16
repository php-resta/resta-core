<?php

namespace Resta;

use Resta\Config\ConfigProcess;
use Store\Services\Cache;
use Store\Services\HttpSession as Session;
use Store\Services\Redis as Redis;
use Store\Services\AppCollection as Collection;
use Store\Services\DateCollection as Date;
use Lingua\Lingua;

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
     * @return \stdClass
     */
    public static function kernelBindObject(){
        return new \stdClass;
    }

    /**
     * @return mixed
     */
    public static function getAppInstance(){

        //we save an instance for the entire application
        //and add it to the helper file to be accessed from anywhere in the application.
        if(!isset(self::$instance['appInstance'])){
            self::$instance['appInstance']=unserialize(base64_decode(appInstance));
            return self::$instance['appInstance'];
        }
        return self::$instance['appInstance'];

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

        //if $name starts with $needles for source
        if(Str::endsWith($service,'Source')){
            return self::source($service,$arg);
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
     * @param bool $namespace
     * @return mixed
     */
    public static function repository($service,$namespace=false){

        //I can get the repository name from the magic method as a salt repository,
        //after which we will edit it as an adapter namespace.
        $repositoryName=ucfirst(preg_replace('@Repository@is','',$service));

        //If we then configure the name of the simple repository to be an adapter
        //then we will give the user an example of the adapter class in each repository call.
        $repositoryAdapterName  = $repositoryName.'Adapter';
        $repositoryNamespace    = StaticPathModel::appRepository().'\\'.$repositoryName.'\\'.$repositoryAdapterName;

        if($namespace) return $repositoryNamespace;

        //and eventually we conclude the adapter class of the repository package as an instance.
        return app()->makeBind($repositoryNamespace)->adapter();
    }

    /**
     * @param $arg
     * @return Cache
     */
    private static function cache($arg){
        return new Cache();
    }

    /**
     * @param $service
     * @param $arg
     * @return mixed
     */
    private static function source($service,$arg){

        //get Source path
        $service=ucfirst($service);
        $getCalledClass=str_replace('\\'.class_basename($arg[0]),'',get_class($arg[0]));
        $getCalledClass=class_basename($getCalledClass);

        $service=str_replace($getCalledClass,'',$service);

        //run service for endpoint
        $serviceSource=StaticPathModel::appSourceEndpoint().'\\'.$getCalledClass.'\\'.$service.'\Main';
        return app()->makeBind($serviceSource);
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
     * @return ConfigProcess
     */
    public function configLoaders($config=null){

        return (new ConfigProcess($config))->get();
    }

    /**
     * @param $object
     */
    public function createAppInstance($object){

        if(!defined('appInstance')){
            define('appInstance',(base64_encode(serialize($object))));
        }

    }

    /**
     * @param $instance
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function container($instance,$class,$bind=array()){

        if(!property_exists($instance->container(),$class)){
            throw new \InvalidArgumentException('container object false for ('.$class.') object');
        }

        if(!is_array($instance->container()->{$class}) AND Utils::isNamespaceExists($container=$instance->container()->{$class})){
            return $instance->makeBind($container,$bind);
        }
        return $instance->container()->{$class};
    }

    /**
     * @param null $param
     */
    public function route($param=null){

        $kernel=self::getAppInstance()->app->kernel;

        $saltRouteParameters=$kernel->routeParameters;
        $urlMethod=strtolower($kernel->url['method']);

        $serviceConfRouteParameters=[];
        if(isset($kernel->serviceConf['routeParameters'][$urlMethod])){
            $serviceConfRouteParameters=$kernel->serviceConf['routeParameters'][$urlMethod];
        }


        $list=[];

        foreach ($saltRouteParameters as $key=>$value){
            if(isset($serviceConfRouteParameters[$key])){
                $list[$serviceConfRouteParameters[$key]]=$value;
            }
            else{
                $list[$key]=$value;
            }
        }

        if($param===null){
            return $list;
        }

        return (isset($list[$param])) ? strtolower($list[$param]) : null;

    }

    /**
     * @param $output
     * @param $file
     * @param $type
     * @return mixed
     */
    public function logger($output,$file,$type){

        return app()->singleton()->loggerService->logHandler($output,$file,$type);
    }

    /**
     * @param $data
     * @param array $select
     * @return mixed|string
     */
    public function translator($data,$select=array()){

        //
        $lang=(new Lingua(app()->path()->appLanguage()));

        //
        $base=app()->makeBind(StaticPathModel::appBase());

        $defaultLocale=property_exists($base,'locale') ? $base->locale : 'en';

        if(method_exists($base,'locale')){
            $defaultLocale=$base->locale();
        }

        if(count($select)){
            return $lang->include(['default'])->locale($defaultLocale)->get($data,$select);
        }

        return $lang->include(['default'])->locale($defaultLocale)->get($data);

    }

}