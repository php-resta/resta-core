<?php

namespace Resta;

class ClosureDispatcher {

    /**
     * @var $bind
     */
    protected $bind;

    /**
     * ClosureDispatcher constructor.
     * @param $bind
     */
    public function __construct($bind) {
        $this->bind=$bind;
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function call(\Closure $closure) {
        if(!is_null($this->bind))
            $closure = \Closure::bind($closure, $this->bind, $this->bind);

        return $closure();
    }
}