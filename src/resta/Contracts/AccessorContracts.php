<?php

namespace Resta\Contracts;

interface AccessorContracts
{
    /**
     * @return mixed
     */
    public function get();

    /**
     * @return mixed
     */
    public function set();
}