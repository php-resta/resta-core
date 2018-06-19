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
                $param[$parameter->getName()]=$resolveSpecialNameContainer->getNameContainer($parameter->getDefaultValue());
                return $param;
            }
        }

        $param[$parameter->getName()]=$parameter->getDefaultValue();
        return $param;
    }

}