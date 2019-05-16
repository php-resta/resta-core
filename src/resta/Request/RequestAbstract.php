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
     * get auto generators data
     *
     * @return mixed
     */
    protected function getAutoGenerators()
    {
        return $this->auto_generators;
    }

    /**
     * get auto_generators_dont_overwrite
     *
     * @return mixed
     */
    protected function getAutoGeneratorsDontOverwrite()
    {
        return $this->auto_generators_dont_overwrite;
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
     * get generators data
     *
     * @return mixed
     */
    protected function getGenerators()
    {
        return $this->generators;
    }

    /**
     * get auto_generators_dont_overwrite
     *
     * @return mixed
     */
    protected function getGeneratorsDontOverwrite()
    {
        return $this->generators_dont_overwrite;
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