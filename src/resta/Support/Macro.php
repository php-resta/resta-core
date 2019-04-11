<?php

namespace Resta\Support;

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
     * @var $method
     */
    protected $method;

    /**
     * get macro object
     *
     * @param callable $callback
     */
    public function get($childClass,callable $callback)
    {
        if($this->isMacro){
            return $this->app->resolve($this->macro)->macro($this->method,$childClass);
        }
        return call_user_func($callback);
    }

    /**
     * is availability macro for class
     *
     * @param $method
     * @return bool
     */
    public function isMacro($method)
    {
        // if the macro class is a valid object,
        // then this macro will return a boolean value if it has the specified methode.
        if(is_string($this->macro) &&
            Utils::isNamespaceExists($this->macro) &&
            method_exists($this->app->resolve($this->macro),$method)){

            $this->isMacro  = true;
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