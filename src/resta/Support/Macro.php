<?php

namespace Resta\Support;

use Resta\Contracts\MacroAbleContracts;
use Resta\Foundation\ApplicationProvider;

class Macro extends ApplicationProvider
{
    /**
     * @var bool $isMacro
     */
    protected $isMacro = false;

    /**
     * @var $macro
     */
    protected $macro;

    /**
     * @var $class
     */
    protected $class;

    /**
     * check conditions for macro
     *
     * @return bool
     */
    protected function checkMacroConditions()
    {
        return is_string($this->macro) &&
        Utils::isNamespaceExists($this->macro) &&
        $this->app->resolve($this->macro) instanceof MacroAbleContracts;
    }

    /**
     * get macro object
     *
     * @param $method
     * @param callable $callback
     * @return mixed
     */
    public function get($method,callable $callback)
    {
        if($this->isMacro){
            if(method_exists($resolve = $this->app->resolve($this->macro),$method)){
                return $resolve->macro($this->class);
            }
        }
        return call_user_func($callback);
    }

    /**
     * is availability macro for class
     *
     * @param $class
     * @return $this
     */
    public function isMacro($class)
    {
        // if the macro class is a valid object,
        // then this macro will return a boolean value if it has the specified methode.
        if($this->checkMacroConditions()){

            $this->isMacro  = true;
            $this->class    = $class;
        }

        return $this;
    }

    /**
     * check been runnable with which macro
     *
     * @param $macro
     * @param $concrete
     * @param $method
     * @return mixed
     */
    public function with($macro,$concrete,$method)
    {
        if($this->macro === null){
            return $this($macro)->isMacro($concrete)->get($method,function() use($concrete){
                return $concrete;
            });
        }
    }

    /**
     * invoke construct for macro
     *
     * @param null $macro
     * @return $this
     */
    public function __invoke($macro=null)
    {
        if($macro!==null){
            $this->macro = $macro;
        }
        return $this;
    }
}