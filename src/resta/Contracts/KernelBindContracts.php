<?php

namespace Resta\Contracts;

interface KernelBindContracts {

    /**
     * @return mixed
     */
    public function handle(callable $handle);

}