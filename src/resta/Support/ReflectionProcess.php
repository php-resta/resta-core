<?php

namespace Resta\Support;

class ReflectionProcess
{
    /**
     * @var $namespace
     */
    protected $namespace;

    /**
     * @var $reflection
     */
    protected $reflection;

    /**
     * @var $request
     */
    protected $request;

    /**
     * Controller constructor.
     * @param $namespace
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param $method
     * @return object
     * @throws \ReflectionException
     */
    public function reflectionMethodParams($method)
    {
        $reflection = new \ReflectionMethod($this->namespace,$method);

        return (object)[
            'reflection'    => $reflection,
            'document'      => $reflection->getDocComment(),
            'parameters'    => $reflection->getParameters(),
        ];
    }
}