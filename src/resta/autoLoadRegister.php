<?php

namespace Resta;

class autoloadRegister
{
    /**
     * @var $class
     */
    private $class;

    /**
     * @var $classPath
     */
    private $classPath;

    /**
     * @return void
     */
    public function register()
    {
        // Use default autoload implementation
        spl_autoload_register(function($class){
            $this->getRegisterCallBackVar($class);
            $this->registerCallBackFormatter();
        });
    }

    /**
     * @param $class
     */
    private function getRegisterCallBackVar($class)
    {
        $this->class=$class;
        $this->classPath=root.'/'.$this->class.'.php';
        $this->classPath=str_replace("\\","/",$this->classPath);
    }

    /**
     * return mixed
     */
    private function registerCallBackFormatter ()
    {
        $this->checkAliasClassFormatter($this->classPath,function() {
            require($this->classPath);
        });
    }

    /**
     * @param $class
     * @param $callback
     * @return mixed
     */
    private function checkAliasClassFormatter($class,$callback)
    {
        $systemApp=[];

        if(defined('app')){
            $systemApp=(new \Resta\Support\ClassAliasGroup())->handle($class);
        }

        if(!file_exists($class)){
            $this->getAliasClassFormatter($class,$systemApp);
            return false;
        }

        return call_user_func($callback);
    }

    /**
     * @param $class
     * @param $systemApp
     */
    private function getAliasClassFormatter($class,$systemApp)
    {
        $this->setAliasClassGroup($class,$systemApp);
    }

    /**
     * @param $class
     * @param $systemApp
     */
    private function setAliasClassGroup($class,$systemApp)
    {
        $alias=str_replace(root.'/','',$class);
        $alias=str_replace('.php','',$alias);

        //set class_alias groups
        if(array_key_exists($alias,$systemApp)){
            class_alias($systemApp[$alias],$alias);
        }
    }
}

