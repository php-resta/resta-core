<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Foundation\Console as ConsoleFounder;

class AppConsole extends ApplicationProvider  {

    /**
     * @param $base
     * @param $adapter
     */
    public function kernelConsole(){

        //console object is registered to the kernel object.
        $this->singleton()->console=($this->app->console()) ? (new ConsoleFounder())->handle($this->app) : null;
    }

}