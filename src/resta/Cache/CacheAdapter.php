<?php

namespace Resta\Cache;

use Resta\Support\Macro;
use Store\Services\Cache;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheAdapter
{
    /**
     * cache file adapter
     *
     * return mixed
     */
    private function file()
    {
        return new FilesystemAdapter(

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
    private function redis()
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

    /**
     * check parent class or child class for adapter method
     *
     * @param $name
     * @param $class
     * @return mixed
     */
    public function __call($name, $class)
    {
        return app()['macro'](Cache::class)->isMacro($name,pos($class))->get(function() use($name){
            return $this->{$name}();
        });
    }
}