<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Environment\EnvironmentConfiguration;

class Environment extends ApplicationProvider {

    public function boot(){

        $this->app->bind('environment',function(){
            return EnvironmentConfiguration::class;
        });
    }
}