<?php

namespace Resta\Request;

use Resta\Support\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;

abstract class RequestAnnotationAbstract extends ApplicationProvider
{
    /**
     * @var string $request
     */
    protected $request;

    /**
     * @var array $inputs
     */
    protected $inputs = [];

    /**
     * @param $method
     * @param $key
     * @return mixed
     */
    abstract function annotation($method,$key);

    /**
     * get input values from request object
     *
     * @return mixed
     */
    public function getInputs()
    {
        $this->inputs = $this->getReflection('inputs');
    }

    /**
     * get reflection from request object
     *
     * @param $param
     * @return mixed
     */
    public function getReflection($param)
    {
        return $this->request->call(function() use ($param) {
            return $this->{$param};
        });
    }

    /**
     * set reflection for request object
     *
     * @param $reflection
     */
    public function setReflection($reflection){

        $this->request = ClosureDispatcher::bind($reflection);
    }
}