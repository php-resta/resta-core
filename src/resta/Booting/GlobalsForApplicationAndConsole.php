<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\GlobalLoaders\ForAppAndConsole;

class GlobalsForApplicationAndConsole extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //If the second parameter is sent true to the application builder,
        //all operations are performed by the console and the custom bootings are executed
        $this->makeBind(ForAppAndConsole::class)->forAppAndConsole();
    }

}