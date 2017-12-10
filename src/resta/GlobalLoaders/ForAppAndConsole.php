<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Foundation\Console;

class ForAppAndConsole extends ApplicationProvider  {

    /**
     * @method environment
     * @param null $environment
     */
    public function forAppAndConsole($environment=null){

        //If the second parameter is sent true to the application builder,
        //all operations are performed by the console and the custom bootings are executed
        $this->singleton()->console=($this->app->console()) ? (new Console())->handle($this->app) : null;
    }

}