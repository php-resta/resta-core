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

        $this->app->bind('environment',function(){
            return EnvironmentConfiguration::class;
        },true);
    }
}