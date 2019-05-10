<?php

namespace Resta\Contracts;

interface ClosureDispatcherContracts {

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function call(\Closure $closure);
}