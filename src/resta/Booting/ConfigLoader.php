<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Config\ConfigLoader as Config;

class ConfigLoader extends ApplicationProvider {

    public function boot(){

        $this->app->bind('config',function(){
            return Config::class;
        });
    }
}