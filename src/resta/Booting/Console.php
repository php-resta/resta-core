<?php

namespace Resta\Booting;

use Resta\Utils;
use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Console\Console as ConsoleManager;

class Console extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        //if the console is true
        //console app runner
        if(Utils::isRequestConsole()){

            //If the second parameter is sent true to the application builder,
            //all operations are performed by the console and the custom booting are executed
            $this->app->bind('appConsole',ConsoleManager::class,true);
        }
    }
}