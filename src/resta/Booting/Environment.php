<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Environment\EnvironmentConfiguration;

class Environment extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // it is often helpful to have different configuration values based on
        // the environment where the application is running.for example,
        // you may wish to use a different cache driver locally than you do on your production server.
        $this->app->bind('environment',function(){
            return EnvironmentConfiguration::class;
        },true);
    }
}