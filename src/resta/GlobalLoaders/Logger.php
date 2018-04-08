<?php

namespace Resta\GlobalLoaders;

use Monolog\Handler\StreamHandler;
use Resta\Traits\LoggerTraits;
use Resta\Traits\InstanceRegister;
use Store\Services\Redis as Redis;
use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Monolog\Logger as Log;

class Logger extends ApplicationProvider  {

    //Instance register
    use InstanceRegister;

    /**
     * @param $base
     * @param $adapter
     * @param $loggerService
     */
    public function adapterProcess($base,$adapter,$loggerService){

        //We take the adapter attribute for the log log
        //from the service log class and save it to the kernel object.
        $this->register('loggerService',$loggerService);
        $this->register('log',$adapter,$base);
    }

}