<?php

namespace Resta\Foundation;

use Resta\Support\ClassAliasGroup;

class ApplicationAutoLoadRegister
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $classPath;

    /**
     * @var string
     */
    private const FileExtension = '.php';

    /**
     * Register given function as __autoload() implementation
     *
     * @return void
     */
    public function register() : void
    {
        // Use default autoload implementation
        // Register given function as __autoload() implementation
        spl_autoload_register(function($class){
            $this->getRegisterCallBackVar($class);
            $this->registerCallBackFormatter();
        });
    }

    /**
     * calling classes in the default path
     *
     * @param $class
     * @return void
     */
    private function getRegisterCallBackVar($class) :void
    {
        if(defined('root')){

            $this->class = $class;
            $this->classPath = root.''.DIRECTORY_SEPARATOR.''.$this->class.''.self::FileExtension;
            $this->classPath = str_replace("\\","/",$this->classPath);
        }
    }

    /**
     * if there is class alias,this classes will be defined
     *
     * return mixed
     */
    private function registerCallBackFormatter ()
    {
        $this->checkAliasClassFormatter($this->classPath,function() {
            require($this->classPath);
        });
    }

    /**
     * class aliases in the default using
     *
     * @param $class
     * @param $callback
     * @return mixed
     */
    private function checkAliasClassFormatter($class,$callback)
    {
        $systemApp = [];

        if(defined('app')){
            $systemApp = (new ClassAliasGroup())->handle();
        }

        if(!file_exists($class)){
            $this->getAliasClassFormatter($class,$systemApp);
            return false;
        }

        return call_user_func($callback);
    }

    /**
     * class alias formatter
     *
     * @param $class
     * @param $systemApp
     */
    private function getAliasClassFormatter($class,$systemApp)
    {
        $this->setAliasClassGroup($class,$systemApp);
    }

    /**
     * set alias class group
     *
     * @param $class
     * @param $systemApp
     */
    private function setAliasClassGroup($class,$systemApp)
    {
        $alias = str_replace(root.''.DIRECTORY_SEPARATOR.'','',$class);
        $alias = str_replace(self::FileExtension,'',$alias);

        //set class_alias groups
        if(array_key_exists($alias,$systemApp)){
            class_alias($systemApp[$alias],$alias);
        }
    }
}

