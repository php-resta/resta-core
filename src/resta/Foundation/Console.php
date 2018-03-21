<?php

namespace Resta\Foundation;

use Resta\Utils;
use Resta\Console\CustomConsoleProcess;
use Resta\Contracts\ApplicationContracts;

class Console extends Kernel {

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

        //
        $this->consoleClassNamespace='Resta\Console\\Source\\'.$this->getConsoleClass().'\\'.$this->getConsoleClass();

        //
        return $this->checkConsoleNamespace(function(){
            return (new $this->consoleClassNamespace($this->getConsoleArgumentsWithKey(),$this))->{$this->getConsoleClassMethod()}();
        });

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

    /**
     * @method getConsoleArgumentsWithKey
     * @return array
     */
    public function getConsoleArgumentsWithKey(){

        //get console class real arguments
        $getConsoleClassRealArguments=$this->getConsoleClassRealArguments();

        $listKey=[];

        if(count($getConsoleClassRealArguments)===0){
            return $listKey;
        }

        foreach($getConsoleClassRealArguments as $key=>$value){

            if($key=="0"){

                $listKey['project']=$value;
            }
            else{

                $colonExplode=explode(":",$value);
                $listKey[strtolower($colonExplode[0])]=ucfirst($colonExplode[1]);
            }

        }

        //get app version
        $listKey['version']=Utils::getAppVersion($listKey['project']);

        return $listKey;

    }

    /**
     * @param $namespace
     * @param callable $callback
     */
    public function checkConsoleNamespace(callable $callback){

        //
        if(Utils::isNamespaceExists($this->consoleClassNamespace)){
            return call_user_func($callback);
        }

        //
        return (new CustomConsoleProcess($this->getConsoleArgumentsWithKey(),$this))->handle();

    }
}