<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Config\ConfigLoader as Config;

class ConfigLoader extends ApplicationProvider {

    /**
     * @return void
     */
    public function boot(){

        //This is your application's config installer.
        //You can easily access the config variables with the config installer.
        $this->app->bind('config',function(){
            return Config::class;
        },true);
    }
}