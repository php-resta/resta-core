<?php

namespace Resta\Cache;

use InvalidArgumentException;
use Resta\Foundation\ApplicationProvider;

class CacheContainerResolver extends ApplicationProvider
{
    /**
     * cache process for container pipeline
     *
     * @param $callback
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function cacheProcess($callback)
    {
        //we do cache key control for method cache container data.
        if(isset($this->app['cache']['methodCache']['cache'])){

            //get cache data
            $cache = $this->app['cache']['methodCache']['cache'];

            //get cache config variables from application
            $config = $this->app->resolve(CacheConfigDetector::class)->getConfig();

            // we're processing the output using the cache manager's methods.
            // The cache method we are using is going through the cache process.
            return ($this->app->resolve(CacheManager::class))
                ->adapter($cache['adapter'] ?? $config['adapter'])
                ->name($cache['name'])
                ->expire($cache['expire'] ?? $config['expire'])
                ->get(function() use($callback){
                    return call_user_func($callback);
                });
        }

        return call_user_func($callback);
    }

    /**
     * pipeline process for cache container
     *
     * @param $callback
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function __invoke($callback)
    {
        return $this->cacheProcess($callback);
    }
}