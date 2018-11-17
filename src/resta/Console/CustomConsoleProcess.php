<?php

namespace Resta\Console;

use Resta\Utils;
use Resta\StaticPathModel;
use Resta\Console\ConsoleOutputter;

class CustomConsoleProcess extends ConsoleOutputter{

    use ConsoleListAccessor;

    /**
     * @return mixed
     */
    public function handle(){

        // command namespace
        // call custom command namespace
        $commandNamespace       = Utils::getNamespace($this->command()).'\\'.$this->app->getConsoleClass();

        if(!class_exists($commandNamespace)) return null;

        $commandClassResolved   = app()->makeBind($commandNamespace,['argument'=>$this->argument,'app'=>$this->app->app]);

        // closure binding custom command,move custom namespace as specific
        // call prepare commander firstly for checking command builder
        return $this->app->prepareCommander($commandClassResolved,function($commandClassResolved) {

            // call bindings for resolving
            // call with dependency injection resolving
            $commandBindings=[$commandClassResolved,strtolower($this->app->getConsoleClassMethod())];
            return Utils::callBind($commandBindings,appInstance()->providerBinding());

        });
    }
}