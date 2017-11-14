<?php

namespace Resta\Foundation;

use Resta\Utils;

class Console extends Kernel {

    public function handle(){

        return $this->consoleProcess();
    }

    /**
     * @method getArguments
     * @return array
     */
    public function getArguments(){

        //get psr standard console arguments
        return Utils::upperCase(arguments);
    }

    /**
     * @method consoleProcess
     * @return mixed
     */
    public function consoleProcess(){

        $consoleClassNamespace='Resta\Console\\Source\\'.$this->getConsoleClass().'\\'.$this->getConsoleClass();
        return (new $consoleClassNamespace($this->getConsoleClassRealArguments()))->{$this->getConsoleClassMethod()}();
    }

    /**
     * @method getConsoleClass
     * @return mixed
     */
    public function getConsoleClass(){

        return current($this->getArguments());
    }

    /**
     * @method getConsoleClassMethod
     * @return mixed
     */
    public function getConsoleClassMethod(){

        return $this->getArguments()[1];
    }

    /**
     * @method getConsoleClassMethod
     * @return mixed
     */
    public function getConsoleClassRealArguments(){

        return array_slice($this->getArguments(),2);
    }
}