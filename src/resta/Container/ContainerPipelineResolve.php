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
        if(isset($this->app['methodCache']['cache'])){

            $cache = $this->app['methodCache']['cache'];

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
        $callback = $this->cacheProcess($callback);

        return $callback;
    }
}