<?php

namespace Resta\Contracts;

interface WorkerContracts {

    /**
     * @return bool
     */
    public function handle() : bool;

    /**
     * @return int
     */
    public function getSleep() : int ;

}