<?php

namespace Resta\Console;

use Resta\ApplicationProvider;
use Resta\Environment\EnvironmentConfiguration;

class ConsoleBindings extends ApplicationProvider {

    /**
     * @var array $bindings
     */
    private $bindings=[
        'environment'=>EnvironmentConfiguration::class
    ];

    /**
     * @param $object
     * @return void
     */
    public function console($object){

        //we send the value to the bind method without callback after checking the bind object to be loaded for the console.
        //This is the value found in the bindings variable.
        //Thus, we get the binding objects required for the console independently of the http method installation.
        if(isset($this->bindings[$object])){
            $this->app->bind($object,$this->bindings[$object]);
        }

    }
}