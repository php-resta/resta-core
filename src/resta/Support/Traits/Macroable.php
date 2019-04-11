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
     * @var $data
     */
    protected $data;

    /**
     * get macro method
     *
     * @param $method
     * @param $macro
     * @return mixed
     */
   public function macro($method,$data)
   {
       $this->data  = $data;
       $this->macro = $this;

       return $this->{$method}();
   }

    /**
     * get macro property
     *
     * @param $name
     * @return mixed
     */
   public function __get($name)
   {
       return ClosureDispatcher::bind($this->data)->call(function() use($name){
          return $this->{$name};
       });
   }
}