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
     * @var string $macro
     */
    protected $macro;

    /**
     * @var string $class
     */
    protected $class;

    /**
     * @var string
     */
    protected $values;

    /**
     * check conditions for macro
     *
     * @param bool $static
     * @return bool
     */
    protected function checkMacroConditions($static=false)
    {
        return is_string($this->macro) &&
            Utils::isNamespaceExists($this->macro) &&
            $this->checkMacroInstanceOf($static);
    }

    /**
     * check macro instanceOf or static class
     *
     * @param bool $static
     * @return bool
     */
    protected function checkMacroInstanceOf($static=false)
    {
        if($static){
            return true;
        }
        return $this->app->resolve($this->macro) instanceof MacroAbleContracts;
    }

    /**
     * get macro object
     *
     * @param null|string $method
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
     * get values for macro
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * is availability macro for class
     *
     * @param bool $static
     * @param $class
     * @return $this
     */
    public function isMacro($class,$static=false)
    {
        // if the macro class is a valid object,
        // then this macro will return a boolean value if it has the specified methode.
        if($this->checkMacroConditions($static)){

            $this->isMacro  = true;
            $this->class    = $class;
        }

        return $this;
    }

    /**
     * set values for macro
     *
     * @param $values
     * @return mixed
     */
    public function setValues($values)
    {
        return $this->values = $values;
    }

    /**
     * check been runnable with which macro
     *
     * @param $macro
     * @param $concrete
     * @param $method
     * @return mixed
     */
    public function with($macro,$concrete,$method=null)
    {
        if($this->macro === null){
            return $this($macro)->isMacro($concrete)->get($method,function() use($concrete){
                return $concrete;
            });
        }
    }

    /**
     * check been runnable with which static macro
     *
     * @param $macro
     * @param $concrete
     * @param $method
     * @return mixed
     */
    public function withStatic($macro,$concrete)
    {
        return $this($macro)->isMacro($concrete,true)->get(null,is_callable($concrete) ?
            $concrete : function() use($concrete){
                return $concrete;
            });
    }

    /**
     * invoke construct for macro
     *
     * @param null|string $macro
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