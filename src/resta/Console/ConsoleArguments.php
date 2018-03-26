<?php

namespace Resta\Console;

use Resta\Utils;

trait ConsoleArguments {

    /**
     * @method getArguments
     * @return array
     */
    public function getArguments(){

        //get psr standard console arguments
        return Utils::upperCase(arguments);
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
     * @return string
     */
    public function consoleClassNamespace(){
        return 'Resta\Console\\Source\\'.$this->getConsoleClass().'\\'.$this->getConsoleClass();
    }

    /**
     * @param callable $callback
     */
    public function checkMainConsoleRunner(callable $callback){

        if(Utils::isNamespaceExists($this->consoleClassNamespace())===false){
            return call_user_func($callback);
        }

    }

    /**
     * @method defineAppnameForCustomConsole
     * @return void
     */
    public function defineAppnameForCustomConsole(){

        $arguments=$this->getArguments();
        if(!defined('app') and isset($arguments[2])) define('app',$arguments[2]);
    }
}