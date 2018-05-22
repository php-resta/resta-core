<?php

namespace Resta\Container;

class GraceContainer {

    /**
     * @var array $nameContainers
     */
    protected $nameContainers=[
        'route'=>RouteContainer::class
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

        // If the parameter contains a route variable.
        // We do a custom bind for the route
        if(isset($this->nameContainers[$parameter->getName()])){

            // we do the name control for the container here,
            // and if we have the name container we are checking, we make a handle makebind.
            $nameContainers=$this->nameContainers[$parameter->getName()];
            return app()->makeBind($nameContainers)->handle($parameter->getDefaultValue(),$param);
        }

    }

}