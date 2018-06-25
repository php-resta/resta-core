<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;
use Symfony\Component\Stopwatch\Stopwatch;

class RouteWatch extends ApplicationProvider {

    /**
     * @param callable $callback
     * @return mixed
     */
    public function watch(callable $callback){

        if(config('app.watch')){

            // the Stopwatch component provides
            // a way to profile code.
            $memoryUsage = memory_get_usage();

            //get controller output
            $controllerOutput=call_user_func($callback);

            //save to kernel for a variable named conntrollerWatch
            $this->register('controllerWatch', 'memory',memory_get_usage() - $memoryUsage);
        }

        // if watch config is true; in this case
        // we return the previous instance; if it is false, we go back to the closure object.
        return (isset($controllerOutput)) ? $controllerOutput : call_user_func($callback);
    }
}