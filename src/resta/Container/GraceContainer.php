<?php

namespace Resta\Container;

use Resta\Container\NameContainers\RouteContainer as Route;
use Resta\Container\NameContainers\SpecialNameContainer;

class GraceContainer {

    /**
     * @var array $nameContainers
     */
    protected $nameContainers=[
        'route'=>Route::class
    ];

    /**
     * @param $parameter
     * @param $param
     * @return mixed
     */
    public function graceContainerBuilder($parameter,$param){

        // if the parameter is an object
        // but not a container object.
        // we do some useful logic bind for user benefit.
        if($parameter->getType()!==null){
            return app()->makeBind(RepositoryContainer::class)->handle($parameter,$param);
        }

        // In particular, name container values can be specified and
        // they are injected directly into the methods contextually.
        return $this->getNameContainers($parameter,$param);

    }

    /**
     * @param $parameter
     * @param $param
     * @return mixed
     */
    protected function getNameContainers($parameter,$param){

        // If the parameter contains a route variable.
        // We do a custom bind for the route
        if(isset($this->nameContainers[$parameter->getName()])){

            // we do the name control for the container here,
            // and if we have the name container we are checking, we make a handle makebind.
            $nameContainers=$this->nameContainers[$parameter->getName()];
            return app()->makeBind($nameContainers)->resolveContainer($parameter->getDefaultValue(),$param);
        }

        // In particular, name container values can be specified and
        // they are injected directly into the methods contextually.
        if(isset(app()->singleton()->serviceContainer[$parameter->getName()])){
            return app()->makeBind(SpecialNameContainer::class)->resolveContainer($parameter,$param);

        }

    }

}