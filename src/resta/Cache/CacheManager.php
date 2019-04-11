<?php

namespace Resta\Cache;

use Store\Services\Cache;

class CacheManager extends CacheAdapter
{
    /**
     * @var $cache FilesystemAdapter
     */
    protected $cache;

    /**
     * @var int $expire
     */
    protected $expire = 30;

    /**
     * @var string $file
     */
    protected $adapter = 'file';

    /**
     * @var string $name
     */
    protected $name;

    /**
     * change cache adapter
     *
     * @param null $adapter
     * @return $this
     */
    public function adapter($adapter=null)
    {
        if($adapter!==null){
            $this->adapter = $adapter;
        }

        return $this;
    }

    /**
     * cache name
     *
     * @param null $name
     * @return $this
     */
    public function name($name=null)
    {
        //name variable is
        //the name of the cache data set to be created.
        if($name!==null){
            $this->name = $name;
        }

        return $this;
    }

    /**
     * cache expire
     *
     * @param $expire
     * @return $this
     */
    public function expire($expire=null)
    {
        //Cache data is set at the time.
        //Data will be valid in this time.
        if($expire!==null){
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
        //cache adapter state.
        $this->cache = $this->{$this->adapter}($this);

        //With backtrace, we can specify an automatic name.
        //This will automatically detect which service is running in the service.
        $backtrace = debug_backtrace()[1];

        //If name is null, we name it with backtrace.
        if($this->name===null) {
            $this->name = md5($backtrace['function'].'_'.$backtrace['class']);
        }

        // retrieve the cache item
        $cacheItem = $this->cache->getItem($this->name);

        if (!$cacheItem->isHit()) {

            $data=call_user_func($callback);
            $cacheItem->set($data);
            $this->cache->save($cacheItem);
            return $data;
        }

        // retrieve the value stored by the item
        return $cacheItem->get();
    }
}