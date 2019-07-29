<?php

namespace Resta\Cache;

use Resta\Contracts\ApplicationContracts;

class CacheManager extends CacheAdapter
{
    /**
     * @var string
     */
    protected $cache;

    /**
     * @var int $expire
     */
    protected $expire;

    /**
     * @var string $file
     */
    protected $adapter;

    /**
     * @var null $path
     */
    protected $path;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var bool
     */
    protected $cacheServiceProvider = false;

    /**
     * CacheManager constructor.
     * @param ApplicationContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        parent::__construct($app);

        //get configuration variables from application
        $config = $this->app->resolve(CacheConfigDetector::class)->getConfig();

        $this->adapter  = $config['adapter'];
        $this->path     = $config['path'];
        $this->expire   = $config['expire'];
    }

    /**
     * change cache adapter
     *
     * @param null|string $adapter
     * @return $this
     */
    public function adapter($adapter)
    {
        if(!is_null($adapter)){
            $this->adapter = $adapter;
        }

        return $this;
    }

    /**
     * cache service provider register
     *
     * @param $cacheItem
     * @param $cacheProvider
     * @param $data
     */
    private function cacheServiceProvider($cacheItem,$cacheProvider,$data)
    {
        if($cacheProvider($data)){
            $cacheItem->set($data);
            $this->cache->save($cacheItem);
            $this->cacheServiceProvider = true;
        }

        return $data;
    }

    /**
     * get container cache service provider
     *
     * @param callable $callback
     * @param $cacheItem
     * @param $data
     * @return bool|mixed
     */
    private function containerCacheServiceProvider(callable $callback,$cacheItem,$data)
    {
        if($this->app->has('cache.class')){

            $class = $this->app->get('cache.class');

            if(is_callable(
                $cacheProvider = $this->app->get('cache.'.class_basename($class[0]).':'.$class[1]))
            ){

                return $this->cacheServiceProvider($cacheItem,$cacheProvider,$data);
            }
            elseif(is_callable(
                $cacheProvider = $this->app->get('cache.'.class_basename($class[0])))
            )
            {
                return $this->cacheServiceProvider($cacheItem,$cacheProvider,$data);
            }
        }

        return call_user_func($callback);
    }

    /**
     * cache name
     *
     * @param null|string $name
     * @return $this
     */
    public function name($name)
    {
        //name variable is
        //the name of the cache data set to be created.
        if(!is_null($name)){
            $this->name = $name;
        }

        return $this;
    }

    /**
     * cache expire
     *
     * @param int|mixed $expire
     * @return $this
     */
    public function expire($expire)
    {
        //Cache data is set at the time.
        //Data will be valid in this time.
        if(is_numeric($expire)){
            $this->expire = $expire;
        }

        return $this;
    }

    /**
     * get cache
     *
     * @param callable $callback
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(callable $callback)
    {
        // this class has a macro that can be managed by the user.
        // macros work as an extensible version of the classes.
        $macro = $this->app['macro']->with(config('kernel.macros.cache'),$this,$this->adapter);

        //set cache macroable object
        $this->cache = $macro->{$this->adapter}($callback);

        //With backtrace, we can specify an automatic name.
        //This will automatically detect which service is running in the service.
        $backtrace = debug_backtrace()[1];

        //If name is null, we name it with backtrace.
        if($this->name===null) {
            $this->name = md5($backtrace['function'].'_'.$backtrace['class']);
        }

        //this method may show continuity depending on the macro.
        if(false === $this instanceof $macro) return ;

        // retrieve the cache item
        $cacheItem = $this->cache->getItem($this->name);

        if (!$cacheItem->isHit()) {

            $data = call_user_func($callback);

            return $this->containerCacheServiceProvider(function() use ($data,$cacheItem){
                $cacheItem->set($data);
                $this->cache->save($cacheItem);

                return $data;

            },$cacheItem,$data);
        }

        $this->app->register('illuminator','cache',['name'=>$this->name]);

        // retrieve the value stored by the item
        return $cacheItem->get();
    }

    /**
     * check macro availability for adapter method
     *
     * @param $class
     * @return mixed
     */
    private function macro($class)
    {
        return app()['macro'](Cache::class)->isMacro($class)->get(function() use($class){
            return $class;
        });
    }
}