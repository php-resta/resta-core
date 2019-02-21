<?php

namespace Resta\Console;

use Resta\Support\Utils;
use Resta\StaticPathModel;
use Resta\Support\ClosureDispatcher;
use Resta\Console\ConsoleOutputter;

class CustomConsoleProcess extends ConsoleOutputter
{
    use ConsoleListAccessor;

    /**
     * @return mixed
     */
    public function handle()
    {

        // command namespace
        // call custom command namespace
        $commandNamespace = Utils::getNamespace($this->command()) . '\\' . $this->app->getConsoleClass();

        //return null if there is no command namespace
        if (!class_exists($commandNamespace)) return null;

        //get class resolved
        $commandClassResolved = app()->makeBind($commandNamespace,
            [
                'argument' => $this->argument,
                'app' => $this->app->app
            ]);

        $app = $this->app;

        // closure binding custom command,move custom namespace as specific
        // call prepare commander firstly for checking command builder
        return ClosureDispatcher::bind($app)->call(function () use($commandClassResolved,$app) {

            $this->prepareCommander($commandClassResolved, function($commandClassResolved) use($app) {

                // call bindings for resolving
                // call with dependency injection resolving
                $commandBindings=[$commandClassResolved,strtolower($app->getConsoleClassMethod())];
                return Utils::callBind($commandBindings,appInstance()->providerBinding());

            });
        });

    }
}