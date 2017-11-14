<?php

namespace Resta\Booting;

use Symfony\Component\HttpFoundation\Request;
use Resta\ApplicationProvider;
use Resta\Foundation\Console;

class GlobalAccessor extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        $this->app->singleton()->request=Request::createFromGlobals();

        //We determine with the kernel object which HTTP method the requested from the client
        $this->app->singleton()->httpMethod=$this->app->kernel()->request->getRealMethod();

        //If the second parameter is sent true to the application builder,
        //all operations are performed by the console and the custom bootings are executed
        $this->app->singleton()->console=($this->app->console()) ? (new Console())->handle() : null;
    }

}