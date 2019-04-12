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
     * @var $method
     */
    protected $method;

    /**
     * check conditions for macro
     *
     * @param $method
     * @return bool
     */
    protected function checkMacroConditions($method)
    {
        return is_string($this->macro) &&
        Utils::isNamespaceExists($this->macro) &&
        $this->app->resolve($this->macro) instanceof MacroAbleContracts &&
        method_exists($this->app->resolve($this->macro),$method);
    }

    /**
     * get macro object
     *
     * @param callable $callback
     */
    public function get(callable $callback)
    {
        if($this->isMacro){
            return $this->app->resolve($this->macro)->macro($this->method,$this->class);
        }
        return call_user_func($callback);
    }

    /**
     * is availability macro for class
     *
     * @param $class
     * @param $method
     * @return $this
     */
    public function isMacro($method,$class)
    {
        // if the macro class is a valid object,
        // then this macro will return a boolean value if it has the specified methode.
        if($this->checkMacroConditions($method)){

            $this->isMacro  = true;
            $this->class    = $class;
            $this->method   = $method;
        }

        return $this;
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