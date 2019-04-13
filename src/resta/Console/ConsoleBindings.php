<?php

namespace Resta\Console;

use Resta\Config\ConfigLoader as Config;
use Resta\Foundation\ApplicationProvider;
use Resta\Encrypter\Encrypter as EncrypterProvider;
use Resta\Support\Utils;

class ConsoleBindings extends ApplicationProvider {

    //console arguments trait
    use ConsoleArguments;

    /**
     * @var array $bindings
     */
    private $bindings;

    /**
     * @method bindConsoleShared
     * @return void
     */
    private function bindConsoleShared(){

        //We assign the values assigned to the console object to the bindings array.
        //The console object represents the classes to be bound for the kernel object console.
        //if the array returns false on an if condition, the array will be automatically detected as empty.
        if(isset($this->app['consoleShared']) and is_array($this->app['consoleShared'])){
            return $this->app['consoleShared'];
        }
        return [];
    }

    /**
     * @param $object
     * @param $container
     * @return void
     */
    public function bindForAppCommand($object,$container){

        //We need a definition of the application name from the data supplied with the console variables.
        //The specified app variable is important to us in the command that is run in the application.
        $this->defineAppnameForCustomConsole();

        //We assign the values assigned to the console object to the bindings array.
        //The console object represents the classes to be bound for the kernel object console.
        $this->bindings = $this->bindConsoleShared();

        //if the container value is true,
        //we will have to assign a value to the build method,
        //which is the container method directly.
        $appresolve = ($container) ? 'containerBuild' : 'make';

        //we send the value to the bind method without callback after checking the bind object to be loaded for the console.
        //This is the value found in the bindings variable.
        //Thus, we get the binding objects required for the console independently of the http method installation.
        if(isset($this->bindings[$object])){
            $this->app->{$appresolve}($object,$this->bindings[$object]);
        }
    }

    /**
     * @param $object
     * @param $callback
     * @param $container
     */
    public function console($object,$callback,$container){

        //If the console argument is an operator that exists in the resta kernel,
        //we run a callback method to check it. The bindings for the custom application commander will be run.
        $this->bindForAppCommand($object,$container);

        //closure object
        $callBackResolve = Utils::callbackProcess($callback);

        // if the callbackresolve variable represents a class,
        // we directly register it's resolved status to the container object.
        if(Utils::isNamespaceExists($callBackResolve)){
            $this->app->register($object,$this->app->resolve(Utils::callbackProcess($callback)));
        }
        else{

            // if callbackresolve doesn't represent a class,
            // we're register it's no resolved status.
            $this->app->register($object,Utils::callbackProcess($callback));
        }


    }
}