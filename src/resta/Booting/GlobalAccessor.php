<?php

namespace Resta\Booting;

use Symfony\Component\HttpFoundation\Request;
use Resta\ApplicationProvider;

class GlobalAccessor extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //The HttpFoundation component defines an object-oriented layer for the HTTP specification.
        //The Symfony HttpFoundation component replaces these default PHP global variables and functions by an object-oriented layer
        $this->app->singleton()->request=Request::createFromGlobals();
    }

}