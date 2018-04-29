<?php

namespace Resta\Contracts;

interface EncrypterContracts {

    /**
     * @return mixed
     */
    public function boot();

    /**
     * @return mixed
     */
    public function keyGenerate();

}