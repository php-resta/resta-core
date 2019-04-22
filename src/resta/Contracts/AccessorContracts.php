<?php

namespace Resta\Contracts;

interface AccessorContracts
{
    /**
     * @return mixed
     */
    public function get();

    /**
     * @param array $data
     * @return mixed
     */
    public function set($data=array());
}