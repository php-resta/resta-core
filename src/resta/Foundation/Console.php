<?php

namespace Resta\Foundation;

use Resta\Console\ConsoleArguments;
use Resta\Console\Source\Service\Service;
use Resta\Utils;
use Resta\Console\CustomConsoleProcess;
use Resta\Contracts\ApplicationContracts;

class Console extends Kernel {

    use ConsoleArguments;

    /**
     * @var $app
     */
    public $app;

    /**
     * @var $consoleClassNamespace
     */
    public $consoleClassNamespace;

    /**
     * @param ApplicationContracts $app
     * @return mixed
     */
    public function handle(ApplicationContracts $app){

        $this->app=$app;
        return $this->consoleProcess();
    }

    /**
     * @method consoleProcess
     * @return mixed
     */
    public function consoleProcess(){

        //We create a namespace for the console and we assign to a variable the path of this class.
        $this->consoleClassNamespace=$this->consoleClassNamespace();

        //If the console executor is a custom console application; in this case we look at the kernel directory inside the application.
        //If the console class is not available on the kernel of resta, then the system will run the command class in the application.
        return $this->checkConsoleNamespace(function(){
            return (new $this->consoleClassNamespace($this->getConsoleArgumentsWithKey(),$this))->{$this->getConsoleClassMethod()}();
        });

    }

    /**
     * @param $namespace
     * @param callable $callback
     */
    public function checkConsoleNamespace(callable $callback){

        // we check that they are in
        // the console to run the console commands in the kernel.
        if(Utils::isNamespaceExists($this->consoleClassNamespace)){
            return call_user_func($callback);
        }

        // if the kernel console is not found
        // then we check the existence of the specific application command and run it if it is.
        return (new CustomConsoleProcess($this->getConsoleArgumentsWithKey(),$this))->handle();

    }
}