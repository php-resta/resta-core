<?php

namespace Resta\Console;

use Resta\ApplicationProvider;
use Resta\Environment\EnvironmentConfiguration;
use Resta\Encrypter\Encrypter as EncrypterProvider;
use Resta\Config\ConfigLoader as Config;

class ConsoleBindings extends ApplicationProvider {

    /**
     * @var array $bindings
     */
    private $bindings=[
        'environment'   => EnvironmentConfiguration::class,
        'encrypter'     => EncrypterProvider::class,
        'config'        => Config::class
    ];

    /**
     * @param $object
     * @return void
     */
    public function console($object){

        //We need a definition of the application name from the data supplied with the console variables.
        //The specified app variable is important to us in the command that is run in the application.
        $arguments=arguments;
        if(!defined('app') and isset($arguments[3])) define('app',ucfirst($arguments[3]));

        //we send the value to the bind method without callback after checking the bind object to be loaded for the console.
        //This is the value found in the bindings variable.
        //Thus, we get the binding objects required for the console independently of the http method installation.
        if(isset($this->bindings[$object])){
            $this->app->bind($object,$this->bindings[$object]);

            //We do a definition for console called appInstance and
            //it has to host an example of the entire resta kernel.
            if(end($this->bindings)==$this->bindings[$object]){
                define('appInstance',(base64_encode(serialize($this))));
            }
        }

    }
}