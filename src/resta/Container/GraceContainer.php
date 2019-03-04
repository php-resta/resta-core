<?php

namespace Resta\Container;

use Resta\Support\Arr;
use Resta\Container\NameContainers\SpecialNameContainer;
use Resta\Container\NameContainers\RouteContainer as Route;

class GraceContainer
{
    /**
     * @var array $nameContainers
     */
    protected $nameContainers=[
        'route'=>Route::class
    ];

    /**
     * @var $reflection
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
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return array
     */
    protected function getNameContainers($parameter,$param)
    {
        // If the parameter contains a route variable.
        // We do a custom bind for the route
        if($this->checkNameContainer($parameter)){

            // we do the name control for the container here,
            // and if we have the name container we are checking, we make a handle make bind.
            $nameContainers=$this->nameContainers[$parameter->getName()];
            return app()->resolve($nameContainers,[
                'reflection' => $this->reflection
            ])->resolveContainer($parameter->getDefaultValue(),$param);
        }

        // In particular, name container values can be specified and
        // they are injected directly into the methods contextually.
        if(isset(core()->serviceContainer[$parameter->getName()])){
            return app()->resolve(SpecialNameContainer::class)->resolveContainer($parameter,$param);

        }

        return [];
    }

    /**
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return mixed
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

    /**
     * @param $parameter \ReflectionParameter
     * @return bool
     */
    public function checkNameContainer($parameter)
    {
        return isset($this->nameContainers[$parameter->getName()])
            && Arr::isArrayWithCount($parameter->getDefaultValue());
    }
}