<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Logger extends ApplicationProvider
{
    /**
     * @param $base
     * @param $adapter
     * @param $loggerService
     */
    public function setLogger($base,$adapter,$loggerService){

        //We take the adapter attribute for the log log
        //from the service log class and save it to the kernel object.
        $this->register('logger',app()->namespace()->logger());
        $this->register('loggerService',$loggerService);
        $this->register('log',$adapter,$base);
    }
}