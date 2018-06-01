<?php

namespace Resta\Console;

use Resta\Console\ConsoleOutputter;
use Resta\StaticPathModel;
use Resta\Utils;

class CustomConsoleProcess extends ConsoleOutputter{

    use ConsoleListAccessor;

    /**
     * @var $console \Resta\Foundation\Console
     */
    protected $console;

    public function handle(){

        dd($this->app->getConsoleClass());
        $commandNamespace=Utils::getNamespace($this->command()).'\\'.$this->app->getConsoleClass();
        dd($commandNamespace);
        $commandClassResolved=app()->makeBind($commandNamespace,['argument'=>$this->argument,'app'=>$this->app->app]);
        return Utils::callBind([$commandClassResolved,strtolower($this->app->getConsoleClassMethod())],appInstance()->providerBinding());
    }
}