<?php

namespace Resta\Contracts;

interface WorkerContracts
{
    /**
     * @return mixed
     */
    public function handle();

    /**
     * @return int
     */
    public function getSleep() : int;

}