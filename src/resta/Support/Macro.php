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
    public function get($method=null,callable $callback)
    {
        if($this->isMacro){

            if(is_null($method) && Utils::isNamespaceExists($this->macro)){
                return $this->app->resolve($this->macro);
            }

            if(method_exists($resolve = $this->app->resolve($this->macro),$method)){
                return $resolve->macro($this->class);
            }
        }
        return call_user_func($callback);
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
        if($this->macro === null){

            return $this($macro)->isMacro($concrete,true)->get(null,is_callable($concrete) ?
                $concrete : function() use($concrete){
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