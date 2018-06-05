<?php

namespace Resta\Console;

use Resta\ClosureDispatcher;
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

        //command namespace
        $commandNamespace=Utils::getNamespace($this->command()).'\\'.$this->app->getConsoleClass();

        //call custom command namespace
        $commandClassResolved=app()->makeBind($commandNamespace,['argument'=>$this->argument,'app'=>$this->app->app]);

        // closure binding custom command,move custom namespace as specific
        $closureCommand=app()->makeBind(ClosureDispatcher::class,['bind'=>$commandClassResolved]);

        //call prepare commander firstly for checking command builder
        $prepapareCommander=$commandClassResolved->prepareCommander($closureCommand);

        if(!$prepapareCommander['status']){
            return $commandClassResolved->exception($prepapareCommander);
    }

        // call bindings for resolving
        // call with dependency injection resolving
        $commandBindings=[$commandClassResolved,strtolower($this->app->getConsoleClassMethod())];
        return Utils::callBind($commandBindings,appInstance()->providerBinding());
    }
}