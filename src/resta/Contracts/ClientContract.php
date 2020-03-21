<?php

namespace Resta\Contracts;

interface ClientContract
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param $except
     * @return mixed
     */
    public function except($except);

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

    /**
     * @param $key
     * @return mixed
     * @return void
     */
    public function remove($key);

    /**
     * @return array
     */
    public function request();

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key,$value);

    /**
     * @return mixed
     */
    public function reset();
}