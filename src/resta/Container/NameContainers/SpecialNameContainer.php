<?php

namespace Resta\Container\NameContainers;

class SpecialNameContainer {

    /**
     * @param $parameter
     * @return array
     */
    public function resolveContainer($parameter,$param){

        $specialNameContainer=app()->singleton()->serviceContainer[$parameter->getName()];

        if(class_exists($specialNameContainer)){

            $resolveSpecialNameContainer=app()->makeBind($specialNameContainer);

            //
            if(method_exists($resolveSpecialNameContainer,'getNameContainer')){

                $getParams=($parameter->isDefaultValueAvailable()) ? $parameter->getDefaultValue() : null;
                $param[$parameter->getName()]=$resolveSpecialNameContainer->getNameContainer($getParams);

                //return $param
                return $param;
            }
        }

        $param[$parameter->getName()]=$parameter->getDefaultValue();
        return $param;
    }

}