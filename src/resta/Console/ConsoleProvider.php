<?php

namespace Resta\Console;

use Resta\Support\Utils;
use Resta\Support\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;
use Resta\Contracts\ConsoleOutputterContracts;

class ConsoleProvider extends ApplicationProvider
{
    //get console arguments
    use ConsoleArguments;

    /**
     * @var string
     */
    protected $consoleClassNamespace;

    /**
     * check console namespace
     *
     * @param callable $callback
     * @return mixed
     */
    public function checkConsoleNamespace(callable $callback)
    {
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
     * console event handler
     *
     * @param array $args
     * @return void|mixed
     */
    protected function consoleEventHandler($args=array())
    {
        if(isset($this->app['eventDispatcher'])){

            $listeners = event()->getListeners();

            if(isset($args['event']) && isset($listeners['console'])){

                if(strtolower($args['event'])!=='default' && isset($listeners['console'][strtolower($args['event'])])){

                    $event = $listeners['console'][strtolower($args['event'])];
                    return call_user_func_array($event,['app'=>$this->app,'args'=>$args,]);
                }
            }

            if(isset($listeners['console']['default'])){

                $event = $listeners['console']['default'];
                return call_user_func_array($event,['args'=>$args,'app'=>$this->app]);
            }
        }

    }

    /**
     * console process
     *
     * @return mixed
     */
    protected function consoleProcess()
    {
        //We create a namespace for the console and we assign to a variable the path of this class.
        $this->consoleClassNamespace = $this->consoleClassNamespace();

        //If the console executor is a custom console application; in this case we look at the kernel directory inside the application.
        //If the console class is not available on the kernel of resta, then the system will run the command class in the application.
        return $this->checkConsoleNamespace(function(){

            if($this->isRunnableKernelCommandList()){
                exception()->badMethodCall('this command is not runnable');
            }

            //get console arguments
            $consoleArguments = $this->getConsoleArgumentsWithKey();

            // we get the instance data of the kernel command class of the system.
            $commander = (new $this->consoleClassNamespace($consoleArguments,$this));

            // we check the command rules of each command class.
            $this->prepareCommander($commander,function($commander){
                return $commander->{$this->getConsoleClassMethod()}();
            });

            //console event handler
            $this->consoleEventHandler($consoleArguments);

        });
    }

    /**
     * console application handle
     *
     * @return void|mixed
     */
    public function handle()
    {
        //get is running console
        if($this->app->runningInConsole()){

            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

            //run console process
            if(count($this->getArguments())){
                return $this->consoleProcess();
            }

            return app()->resolve(ConsoleCommandList::class,
                ['argument'=>[]])->handle();
        }
    }

    /**
     * prepare commander
     *
     * @param ConsoleOutputterContracts $commander
     * @param callable $callback
     * @return mixed
     */
    protected function prepareCommander(ConsoleOutputterContracts $commander,callable $callback)
    {
        // closure binding custom command,move custom namespace as specific
        // call prepare commander firstly for checking command builder
        $closureCommand = app()->resolve(ClosureDispatcher::class,['bind'=>$commander]);

        //assign commander method name
        $closureCommand->prepareBind['methodName'] = $this->getConsoleClassMethod();

        $prepareCommander = $commander->prepareCommander($closureCommand);

        if(!$prepareCommander['status']){
            echo $commander->exception($prepareCommander);
            die();
        }

        //callback custom console
        return call_user_func_array($callback,[$commander]);
    }

    /**
     * is runnable kernel command list
     *
     * @return bool
     */
    private function isRunnableKernelCommandList()
    {
        $commandList = $this->app->commandList();

        //is runnable kernel command conditions
        return !array_key_exists($this->consoleClassNamespace,$commandList) OR
            (array_key_exists($this->consoleClassNamespace,$commandList) AND
                !$commandList[$this->consoleClassNamespace]['isRunnable']);
    }
}