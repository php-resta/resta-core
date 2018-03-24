<?php

namespace Resta\GlobalLoaders;

use Monolog\Handler\StreamHandler;
use Resta\Traits\LoggerTraits;
use Store\Services\Redis as Redis;
use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Monolog\Logger as Log;

class Logger extends ApplicationProvider  {

    /**
     * @param $base
     * @param $adapter
     */
    public function adapterProcess($base,$adapter){

        //We take the adapter attribute for the log log
        //from the service log class and save it to the kernel object.
        $this->singleton()->log[$adapter]=$base;
    }

}