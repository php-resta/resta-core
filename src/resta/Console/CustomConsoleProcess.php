<?php

namespace Resta\Console;

use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Support\ClosureDispatcher;
use Resta\Container\DIContainerManager;
use Resta\Foundation\PathManager\StaticPathModel;

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
        $commandNamespace = Utils::getNamespace(path()->command()) . '\\' . $this->app->getConsoleClass();

        //return null if there is no command namespace
        if (!class_exists($commandNamespace)) return null;

        //get class resolved
        $commandClassResolved = app()->resolve($commandNamespace,
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
                return DIContainerManager::callBind($commandBindings,app()->applicationProviderBinding($this->app));

            });
        });

    }
}