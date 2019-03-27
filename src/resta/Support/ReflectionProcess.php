<?php

namespace Resta\Support;

class ReflectionProcess
{
    /**
     * @var array $singletons
     */
    protected static $singletons = [];

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
    public function __construct($namespace = null)
    {
        if($namespace!==null){
            $this->namespace = $namespace;
        }
    }

    /**
     * @param null $namespace
     * @return $this
     */
    public function __invoke($namespace = null)
    {
        if($namespace!==null){
            $this->namespace = $namespace;
        }
        return $this;
    }

    /**
     * @return \ReflectionClass
     *
     * @throws \ReflectionException
     */
    public function getReflectionClass()
    {
        if(!isset(static::$singletons['reflectionClass'])){
            static::$singletons['reflectionClass'] = new \ReflectionClass($this->namespace);
        }
        return static::$singletons['reflectionClass'];
    }

    /**
     * @param $method
     * @return \ReflectionMethod
     *
     * @throws \ReflectionException
     */
    public function getReflectionMethod($method)
    {
        return new \ReflectionMethod($this->namespace,$method);
    }

    /**
     * @param $method
     * @return object
     *
     * @throws \ReflectionException
     */
    public function reflectionMethodParams($method)
    {
        $reflection = $this->getReflectionMethod($method);

        return (object)[
            'reflection'    => $reflection,
            'document'      => $reflection->getDocComment(),
            'parameters'    => $reflection->getParameters(),
            'isProtected'   => $reflection->isProtected(),
        ];
    }

    /**
     * @return \ReflectionProperty[]
     * 
     * @throws \ReflectionException
     */
    public function getProperties()
    {
        return $this->getReflectionClass()->getProperties();
    }
}