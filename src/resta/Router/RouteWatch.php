<?php

namespace Resta\Router;

use Resta\Foundation\ApplicationProvider;

class RouteWatch extends ApplicationProvider
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function watch(callable $callback)
    {
        if(config('app.watch')){

            // the memory usage component provides
            // a way to profile code.
            $memoryUsage = memory_get_usage();

            //get controller output
            $controllerOutput=call_user_func($callback);

            //save to kernel for a variable named controller Watch
            $this->register('controllerWatch', 'memory',memory_get_usage() - $memoryUsage);
        }

        // if watch config is true; in this case
        // we return the previous instance; if it is false, we go back to the closure object.
        return (isset($controllerOutput)) ? $controllerOutput : call_user_func($callback);
    }
}