<?php

namespace Resta;
/*
|--------------------------------------------------------------------------
| Application Starting
|--------------------------------------------------------------------------
|
| This class is the starter of your application. This class is used when the
| apix firstly calls.all request coming to application are run here.
| Resta starter place
|
*/

class autoloadRegister {

    /**
     * @var $class
     */
    private $class;

    /**
     * @var $classPath
     */
    private $classPath;


    /**
     * spl autoload register
     */
    public function register(){

        // Use default autoload implementation
        spl_autoload_register(function($class){
            $this->getRegisterCallBackVar($class);
            $this->registerCallBackFormatter();
        });
    }

    /**
     * @param $class
     * getRegisterCallBackVar
     */
    private function getRegisterCallBackVar($class){

        $this->class=$class;
        $this->classPath=root.'/'.$this->class.'.php';
        $this->classPath=str_replace("\\","/",$this->classPath);
    }

    /**
     * registerCallBackFormatter
     */
    private function registerCallBackFormatter () {

        $this->checkAliasClassFormatter($this->classPath,function() {
            require($this->classPath);
        });
    }


    /**
     * @param $class
     * @param $callback
     * @return mixed
     */
    private function checkAliasClassFormatter($class,$callback){

        if(!file_exists($class)){
            return $this->getAliasClassFormatter($class);
        }
        return call_user_func($callback);
    }

    /**
     * @param $class
     * @return mixed
     */
    private function getAliasClassFormatter($class){



    }

    /**
     * @param $class
     * @param $systemApp
     * setAliasClassGroup
     */
    private function setAliasClassGroup($class,$systemApp){


    }

}

