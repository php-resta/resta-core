<?php

namespace Resta\Contracts;

interface ConfigProviderContracts {

    /**
     * @param null $path
     * @return mixed
     */
    public function setConfig($path=null);
}