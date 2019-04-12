<?php

namespace Resta\Contracts;

interface MacroAbleContracts
{
    /**
     * @param $method
     * @param $class
     * @return mixed
     */
    public function macro($method,$class);

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name);
}