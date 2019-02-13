<?php

namespace Resta\Console;

use Resta\Support\Utils;
use Resta\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;

class Console extends ApplicationProvider {

    //get console arguments
    use ConsoleArguments;

    /**
     * @var $consoleClassNamespace
     */
    public $consoleClassNamespace;

    /**
     * @param callable $callback
     * @return mixed
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

    /**
     * @method consoleProcess
     * @return mixed
     */
    protected function consoleProcess(){

        //We create a namespace for the console and we assign to a variable the path of this class.
        $this->consoleClassNamespace=$this->consoleClassNamespace();

        //If the console executor is a custom console application; in this case we look at the kernel directory inside the application.
        //If the console class is not available on the kernel of resta, then the system will run the command class in the application.
        return $this->checkConsoleNamespace(function(){

            if($this->isRunnableKernelCommandList()){
                exception()->badMethodCall('this command is not runnable');
            }

            // we get the instance data of the kernel command class of the system.
            $commander=(new $this->consoleClassNamespace($this->getConsoleArgumentsWithKey(),$this));

            // we check the command rules of each command class.
            return $this->prepareCommander($commander,function($commander){
                return $commander->{$this->getConsoleClassMethod()}();
            });
        });

    }

    /**
     * @return mixed
     */
    public function handle()
    {
        //get is running console
        if(Utils::isRequestConsole()){

            //run console process
            if(count($this->getArguments())){
                return $this->consoleProcess();
            }

            return app()->makeBind(ConsoleCommandList::class,
                ['argument'=>[]])->handle();
        }
    }

    /**
     * @param $commander
     * @param callable $callback
     * @return mixed
     */
    protected function prepareCommander($commander,callable $callback){

        // closure binding custom command,move custom namespace as specific
        // call prepare commander firstly for checking command builder
        $closureCommand     = app()->makeBind(ClosureDispatcher::class,['bind'=>$commander]);

        //assign commander method name
        $closureCommand->prepareBind['methodName']=$this->getConsoleClassMethod();
        $prepareCommander   = $commander->prepareCommander($closureCommand);

        if(!$prepareCommander['status']){
            echo $commander->exception($prepareCommander);
            die();
        }

        //callback custom console
        return call_user_func_array($callback,[$commander]);
    }

    /**
     * @return bool
     */
    private function isRunnableKernelCommandList()
    {
        $commandList = Utils::getCommandList();

        //is runnable kernel command conditions
        return !array_key_exists($this->consoleClassNamespace,$commandList) OR
            (array_key_exists($this->consoleClassNamespace,$commandList) AND
                !$commandList[$this->consoleClassNamespace]['isRunnable']);
    }
}