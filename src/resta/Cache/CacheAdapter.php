<?php

namespace Resta\Cache;

use Resta\Foundation\ApplicationProvider;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheAdapter extends ApplicationProvider
{
    /**
     * cache file adapter
     *
     * return mixed
     */
    public function file()
    {
        return new FilesystemAdapter(

        // the subdirectory of the main cache directory where cache items are stored
            $namespace = '',

            // in seconds; applied to cache items that don't define their own lifetime
            // 0 means to store the cache items indefinitely (i.e. until the files are deleted)
            $defaultLifetime = $this->expire,

            // the main cache directory (the application needs read-write permissions on it)
            // if none is specified, a directory is created inside the system temporary directory
            $directory = $this->path
        );

    }

    /**
     * cache redis adapter
     *
     * return mixed
     */
    public function redis()
    {
        return new RedisAdapter(

            $redisClient = \application::redis(),

            // the subdirectory of the main cache directory where cache items are stored
            $namespace = '',

            // in seconds; applied to cache items that don't define their own lifetime
            // 0 means to store the cache items indefinitely (i.e. until the files are deleted)
            $defaultLifetime = $this->expire

        );
    }
}