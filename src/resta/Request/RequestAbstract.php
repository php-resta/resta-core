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
        if(property_exists($this,'auto_generators')){
            return $this->auto_generators;
        }
        return [];

    }

    /**
     * get auto_generators_dont_overwrite
     *
     * @return mixed
     */
    protected function getAutoGeneratorsDontOverwrite()
    {
        if(property_exists($this,'auto_generators_dont_overwrite')){
            return $this->auto_generators_dont_overwrite;
        }
        return [];

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
        if(property_exists($this,'generators')){
            return $this->generators;
        }
        return [];

    }

    /**
     * get auto_generators_dont_overwrite
     *
     * @return mixed
     */
    protected function getGeneratorsDontOverwrite()
    {
        if(property_exists($this,'generators_dont_overwrite')){
            return $this->generators_dont_overwrite;
        }
        return [];

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