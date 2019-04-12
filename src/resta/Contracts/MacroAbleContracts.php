<?php

namespace Resta\Contracts;

interface MacroAbleContracts
{
    /**
     * @param $class
     * @return mixed
     */
    public function macro($class);

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name);
}