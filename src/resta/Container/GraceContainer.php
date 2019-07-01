<?php

namespace Resta\Container;

use Resta\Support\Arr;

class GraceContainer
{
    /**
     * @var array $nameContainers
     */
    protected $nameContainers = [];

    /**
     * @var object $reflection
     */
    protected $reflection;

    /**
     * GraceContainer constructor.
     * @param $reflection
     */
    public function __construct($reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * check name container
     *
     * @param $parameter \ReflectionParameter
     * @return bool
     *
     * @throws \ReflectionException
     */
    public function checkNameContainer($parameter)
    {
        return isset($this->nameContainers[$parameter->getName()])
            && Arr::isArrayWithCount($parameter->getDefaultValue());
    }

    /**
     * get name containers
     *
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return array
     *
     * @throws \ReflectionException
     */
    protected function getNameContainers($parameter,$param)
    {
        // If the parameter contains a route variable.
        // We do a custom bind for the route
        if($this->checkNameContainer($parameter)){

            // we do the name control for the container here,
            // and if we have the name container we are checking, we make a handle make bind.
            $nameContainers = $this->nameContainers[$parameter->getName()];
            return app()->resolve($nameContainers,[
                'reflection' => $this->reflection
            ])->resolveContainer($parameter->getDefaultValue(),$param);
        }

        return [];
    }

    /**
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function graceContainerBuilder($parameter,$param)
    {
        // if the parameter is an object
        // but not a container object.
        // we do some useful logic bind for user benefit.
        if($parameter->getType()!==null){
            return app()->resolve(RepositoryContainer::class)->handle($parameter,$param);
        }

        // In particular, name container values can be specified and
        // they are injected directly into the methods contextually.
        return $this->getNameContainers($parameter,$param);
    }
}