<?php

namespace Resta\Console;


use Resta\ApplicationProvider;

class ConsoleDefinitor extends ApplicationProvider {

    /**
     * @method handle
     * @return mixed
     */
    public function handle(){

        //We assign the kernel object of the console constructor class
        //to be executed for the console and call the global installer.
        $this->singleton()->appConsoleGlobalInstance->kernelConsole();
    }

}