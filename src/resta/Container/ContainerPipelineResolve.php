<?php

namespace Resta\Container;

use Resta\Cache\CacheManager;
use Resta\Foundation\ApplicationProvider;

class ContainerPipelineResolve extends ApplicationProvider
{
    /**
     * cache process for container pipeline
     *
     * @param $callback
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function cacheProcess($callback)
    {
        //we do cache key control for method cache container data.
        if(isset($this->app['methodCache']['cache'])){

            //get cache data
            $cache = $this->app['methodCache']['cache'];

            // we're processing the output using the cache manager's methods.
            // The cache method we are using is going through the cache process.
            return (new CacheManager())
                ->adapter($cache['adapter'] ?? null)
                ->name($cache['name'])
                ->expire($cache['expire'] ?? null)
                ->get(function() use($callback){
                    return call_user_func($callback);
            });
        }

        return call_user_func($callback);
    }

    /**
     * get container pipeline
     *
     * @param callable $callback
     * @return mixed
     */
    public function handle(callable $callback)
    {
        // the container pipeline method fires
        // the handle method and will return feedback after doing a number of operations here.
        $callback = $this->cacheProcess($callback);

        //return callback
        return $callback;
    }
}