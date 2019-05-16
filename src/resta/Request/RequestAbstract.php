<?php

namespace Resta\Request;

abstract class RequestAbstract
{
    /**
     * @var array $inputs
     */
    protected $inputs = [];

    /**
     * @var array $origin
     */
    protected $origin = [];

    /**
     * get inputs
     *
     * @return array
     */
    protected function get()
    {
        return $this->inputs;
    }

    /**
     * get client objects
     *
     * @return array
     */
    protected function getClientObjects()
    {
        return array_diff_key($this->getObjects(),['inputs'=>[]]);
    }

    /**
     * get object vars
     *
     * @return array
     */
    protected function getObjects()
    {
        return get_object_vars($this);
    }

    /**
     * get origin
     *
     * @return array
     */
    protected function getOrigin()
    {
        return $this->origin;
    }
}