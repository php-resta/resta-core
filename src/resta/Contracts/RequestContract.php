<?php

namespace Resta\Contracts;

interface RequestContract
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function input($key, $default=null);

    /**
     * @param $key
     * @return bool
     */
    public function has($key);
}