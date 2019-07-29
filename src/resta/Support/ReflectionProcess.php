<?php

namespace Resta\Support;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionException;

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
     * @var null|string
     */
    protected $documentData;

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
     * get document data
     *
     * @return string|null
     */
    public function getDocumentData()
    {
        return $this->documentData;
    }

    /**
     * @return ReflectionProperty[]
     *
     * @throws ReflectionException
     */
    public function getProperties()
    {
        return $this->getReflectionClass()->getProperties();
    }

    /**
     * @return ReflectionClass
     *
     * @throws ReflectionException
     */
    public function getReflectionClass()
    {
        if(!isset(static::$singletons['reflectionClass'])){
            static::$singletons['reflectionClass'] = new ReflectionClass($this->namespace);
        }
        return static::$singletons['reflectionClass'];
    }

    /**
     * @param $method
     * @return ReflectionMethod
     *
     * @throws ReflectionException
     */
    public function getReflectionMethod($method)
    {
        return new ReflectionMethod($this->namespace,$method);
    }

    /**
     * resolve if the method document is available for container
     *
     * @param null|string $method
     * @param null|string $param
     * @return bool
     *
     * @throws ReflectionException
     */
    public function isAvailableMethodDocument($method=null,$param=null)
    {
        $document = $this->reflectionMethodParams($method)->document;

        if(is_string($document)
            && preg_match('#@'.$param.'\((.*?)\)\r\n#is',$document,$data)) {
            if (is_array($data) && isset($data[1])) {
                $this->documentData = $data[1];
                return true;
            }
        }

        return false;
    }

    /**
     * @param $method
     * @return object
     *
     * @throws ReflectionException
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
}