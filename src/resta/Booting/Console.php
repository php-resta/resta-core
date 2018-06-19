<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Console\Console as ConsoleManager;

class Console extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //if the console is true
        //console app runner
        if($this->app->runningInConsole()){

            //If the second parameter is sent true to the application builder,
            //all operations are performed by the console and the custom bootings are executed
            $this->app->bind('appConsole',ConsoleManager::class,true);
        }
    }


}