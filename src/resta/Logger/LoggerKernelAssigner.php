<?php

namespace Resta\Logger;

use Resta\Foundation\ApplicationProvider;

class LoggerKernelAssigner extends ApplicationProvider
{
    /**
     * @param mixed ...$params
     */
    public function setLogger(...$params){

        // params list
        [$base,$adapter,$loggerService] = $params;

        // we take the adapter attribute for the log log
        // from the service log class and save it to the kernel object.
        $this->app->register('logger',app()->namespace()->logger());
        $this->app->register('loggerService',$loggerService);
        $this->app->register('log',$adapter,$base);
    }
}