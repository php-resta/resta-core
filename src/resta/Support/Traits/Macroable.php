<?php

namespace Resta\Support\Traits;

use Resta\Support\ClosureDispatcher;

trait Macroable
{
    /**
     * @var $macro
     */
    protected $macro;

    /**
     * @var $class
     */
    protected $class;

    /**
     * get macro method
     *
     * @param $class
     * @return mixed
     */
   public function macro($class)
   {
       $this->class  = $class;
       $this->macro = $this;

       return $this;
   }

    /**
     * get macro property
     *
     * @param $name
     * @return mixed
     */
   public function __get($name)
   {
       return ClosureDispatcher::bind($this->class)->call(function() use($name){
          return $this->{$name};
       });
   }
}