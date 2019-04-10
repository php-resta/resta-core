<?php

namespace Resta\Cache;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheAdapter
{
    /**
     * cache file adapter
     *
     * return mixed
     */
    public function file()
    {
        $this->cache = new FilesystemAdapter(

        // the subdirectory of the main cache directory where cache items are stored
            $namespace = '',

            // in seconds; applied to cache items that don't define their own lifetime
            // 0 means to store the cache items indefinitely (i.e. until the files are deleted)
            $defaultLifetime = $this->expire,

            // the main cache directory (the application needs read-write permissions on it)
            // if none is specified, a directory is created inside the system temporary directory
            $directory = app()->path()->appResourche().'/Cache'
        );

    }

    /**
     * cache redis adapter
     *
     * return mixed
     */
    public function redis()
    {
        $this->cache = new RedisAdapter(

            $redisClient = \application::redis(),

            // the subdirectory of the main cache directory where cache items are stored
            $namespace = '',

            // in seconds; applied to cache items that don't define their own lifetime
            // 0 means to store the cache items indefinitely (i.e. until the files are deleted)
            $defaultLifetime = $this->expire

        );

    }
}