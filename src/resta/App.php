<?php

namespace Resta;

use Lingua\Lingua;
use Store\Services\Cache;
use Resta\Config\ConfigProcess;
use Store\Services\Redis as Redis;
use Store\Services\HttpSession as Session;
use Store\Services\DateCollection as Date;
use Store\Services\AppCollection as Collection;

class App
{
    /**
     * @var array
     */
    protected static $instance=[];

    /**
     * @param $service
     * @param $arg
     * @return mixed
     */
    public static function annotationsLoaders($service,$arg)
    {
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
     * @param $service
     * @return mixed
     */
    private static function builder($service)
    {
        //we are making a namespace assignment for the builder.
        $builder=app()->namespace()->builder().'\\'.$service;

        //we are getting builder instance.
        return app()->makeBind($builder);
    }

    /**
     * @return Cache
     */
    private static function cache()
    {
        return new Cache();
    }

    /**
     * @return Collection
     */
    private static function collection()
    {
        return (new Collection());
    }

    /**
     * @param null $config
     * @return mixed|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function configLoaders($config=null)
    {
        return Config\Config::make($config)->get();
    }

    /**
     * @param $instance
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function container($instance,$class,$bind=array())
    {
        if(!property_exists($instance->container(),$class)){
            throw new \InvalidArgumentException('container object false for ('.$class.') object');
        }

        $container=$instance->container()->{$class};

        if(!is_array($instance->container()->{$class}) AND Utils::isNamespaceExists($container)){
            return $instance->makeBind($container,$bind);
        }
        return $instance->container()->{$class};
    }

    /**
     * @param $object
     */
    public function createAppInstance($object)
    {
        if(!defined('appInstance')){
            define('appInstance',(base64_encode(serialize($object))));
        }
    }

    /**
     * @param $arg
     * @return null
     */
    private static function date($arg)
    {
        if(property_exists($class=pos($arg),'app')){
            return $class->makeBind(Date::class);
        }
        return null;
    }

    /**
     * @return mixed
     */
    public static function getAppInstance()
    {
        //we save an instance for the entire application
        //and add it to the helper file to be accessed from anywhere in the application.
        if(!isset(self::$instance['appInstance'])){
            self::$instance['appInstance']=unserialize(base64_decode(appInstance));
            return self::$instance['appInstance'];
        }
        return self::$instance['appInstance'];
    }

    /**
     * @return \stdClass
     */
    public static function kernelBindObject()
    {
        return new \stdClass;
    }

    /**
     * @return Session
     */
    private static function session()
    {
        return new Session();
    }

    /**
     * @param $service
     * @param bool $namespace
     * @return string
     */
    public static function repository($service,$namespace=false)
    {
        //I can get the repository name from the magic method as a salt repository,
        //after which we will edit it as an adapter namespace.
        $repositoryName=ucfirst(preg_replace('@Repository@is','',$service));

        //If we then configure the name of the simple repository to be an adapter
        //then we will give the user an example of the adapter class in each repository call.
        $repositoryAdapterName  = $repositoryName.'Adapter';
        $repositoryNamespace    = app()->namespace()->repository().'\\'.$repositoryName.'\\'.$repositoryAdapterName;

        if($namespace) return $repositoryNamespace;

        //and eventually we conclude the adapter class of the repository package as an instance.
        return app()->makeBind($repositoryNamespace)->adapter();
    }

    /**
     * @param $service
     * @param $arg
     * @return mixed
     */
    private static function source($service,$arg)
    {
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
     * @return mixed
     */
    private static function redis()
    {
        if(!isset(self::$instance['redis'])){

            self::$instance['redis']=(new Redis())->client();
            return self::$instance['redis'];

        }
        return self::$instance['redis'];
    }

    /**
     * @param null $param
     * @return array|null|string
     */
    public function route($param=null)
    {
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
     * @param $data
     * @param array $select
     * @return mixed|string
     */
    public function translator($data,$select=array())
    {
        $lang=(new Lingua(app()->path()->appLanguage()));

        $defaultLocale=config('app.locale');

        if(count($select)){
            return $lang->include(['default'])->locale($defaultLocale)->get($data,$select);
        }

        return $lang->include(['default'])->locale($defaultLocale)->get($data);
    }

}