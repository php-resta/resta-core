<?php

namespace Resta\Console;

use Resta\ApplicationProvider;
use Resta\Environment\EnvironmentConfiguration;
use Resta\Encrypter\Encrypter as EncrypterProvider;
use Resta\Config\ConfigLoader as Config;

class ConsoleBindings extends ApplicationProvider {

    //console arguments trait
    use ConsoleArguments;

    /**
     * @var array $bindings
     */
    private $bindings;

    /**
     * @param $object
     * @return void
     */
    public function console($object){

        //If the console argument is an operator that exists in the resta kernel,
        //we run a callback method to check it. The bindings for the custom application commander will be run.
        $this->checkMainConsoleRunner(function() use($object) {
            $this->bindForAppCommand($object);
        });
    }

    /**
     * @param $object
     */
    public function bindForAppCommand($object){

        //We need a definition of the application name from the data supplied with the console variables.
        //The specified app variable is important to us in the command that is run in the application.
        $this->defineAppnameForCustomConsole();

        //We assign the values assigned to the console object to the bindings array.
        //The console object represents the classes to be bound for the kernel object console.
        $this->bindings=$this->bindConsoleShared();

        //we send the value to the bind method without callback after checking the bind object to be loaded for the console.
        //This is the value found in the bindings variable.
        //Thus, we get the binding objects required for the console independently of the http method installation.
        if(isset($this->bindings[$object])){
            $this->app->bind($object,$this->bindings[$object]);

            //We do a definition for console called appInstance and
            //it has to instance an example of the entire resta kernel.
            if(isset($this->bindings['config'])){
                $this->singleton()->appClass->createAppInstance($this);
            }
        }
    }

    /**
     * @method bindConsoleShared
     * @return void
     */
    private function bindConsoleShared(){

        //We assign the values assigned to the console object to the bindings array.
        //The console object represents the classes to be bound for the kernel object console.
        //if the array returns false on an if condition, the array will be automatically detected as empty.
        if(isset($this->singleton()->consoleShared) and is_array($this->singleton()->consoleShared)){
            return $this->singleton()->consoleShared;
        }
        return [];
    }
}