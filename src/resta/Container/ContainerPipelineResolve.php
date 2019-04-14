<?php

namespace Resta\Container;

use Resta\Cache\CacheContainerResolver;
use Resta\Foundation\ApplicationProvider;

class ContainerPipelineResolve extends ApplicationProvider
{
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
        $callback = $this->app->resolve(CacheContainerResolver::class)->cacheProcess($callback);

        //return callback
        return $callback;
    }
}