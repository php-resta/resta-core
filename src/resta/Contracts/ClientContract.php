<?php

namespace Resta\Contracts;

interface ClientContract
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