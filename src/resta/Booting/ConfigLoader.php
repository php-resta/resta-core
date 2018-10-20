<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Config\ConfigLoader as Config;

class ConfigLoader extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // this is your application's config installer.
        // you can easily access the config variables with the config installer.
        $this->app->bind('config',function(){
            return Config::class;
        },true);
    }
}