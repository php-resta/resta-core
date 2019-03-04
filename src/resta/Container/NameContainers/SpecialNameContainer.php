<?php

namespace Resta\Container\NameContainers;

class SpecialNameContainer
{
    /**
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return mixed
     */
    public function resolveContainer($parameter,$param)
    {
        //we get the custom name container value via serviceContainer.
        $specialNameContainer=app()->singleton()->serviceContainer[$parameter->getName()];

        // we determine whether the value of
        // the private name container is class.
        if(class_exists($specialNameContainer)){

            //we resolve the custom name container through the service container.
            $resolveSpecialNameContainer=app()->resolve($specialNameContainer);

            // we need to find out whether we have
            // the specific name getNameContainer method solved
            // so we can use the special name container value with bind.
            if(method_exists($resolveSpecialNameContainer,'getNameContainer')){

                // when we bind the name container method,
                // we check the existence of the default value so that we can use it directly for lean.
                // and then save it in the parameter context by executing the getNameContainer method.
                $getParams=($parameter->isDefaultValueAvailable()) ? $parameter->getDefaultValue() : null;
                $param[$parameter->getName()]=$resolveSpecialNameContainer->getNameContainer($getParams);

                //return $param
                return $param;
            }
        }

        //parameter context for special name container and return
        $param[$parameter->getName()]=$parameter->getDefaultValue();
        return $param;
    }

}