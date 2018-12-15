<?php

namespace Resta\Contracts;

interface AccessorContracts
{
    /**
     * @return mixed
     */
    public function get();

    /**
     * @param $data
     * @return mixed
     */
    public function set($data);
}