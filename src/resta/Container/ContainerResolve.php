<?php

namespace Resta\Container;

/**
 * Class ContainerResolve
 * @package Resta
 */
class ContainerResolve {

    /**
     * @param $class
     * @param $param
     * @param callable $callback
     */
    public function call($class,$param,callable $callback){

        // We use the reflection class to solve
        // the parameters of the class's methods.
        $param=$this->reflectionMethodParameters($class,$param);

        // as a result
        // we return the resolved class to the callback class
        $params=(object)['class'=>$class,'param'=>$param];
        return call_user_func_array($callback,[$params]);
    }

    /**
     * @param $class
     * @param $param
     */
    private function reflectionMethodParameters($class,$param){

        // With the reflection class we get the method.
        // and then we get the parameters in array.
        $reflection = $this->getReflectionMethod($class);
        $parameters = $reflection->getParameters();

        //service container objects.
        $containers=$this->getContainers();

        // we group the parameters into type and
        // name and bind them with the necessary logic.
        foreach ($parameters as $parameter){

            // if the parameter is an object and
            // this object is a service container object
            // then the parameter will bind.
            $checkParameterForContainer=$this->checkParameterForContainer($containers,$parameter);
            $paramMerge=array_merge($param,$checkParameterForContainer);

            // we do some useful logic bind for user benefit.
            $param=app()->makeBind(GraceContainer::class)->graceContainerBuilder($parameter,$paramMerge);

        }

        return $param;
    }

    /**
     * @param $containers
     * @param $parameter
     * @return array
     */
    private function checkParameterForContainer($containers,$parameter){

        // if the parameter is an object and
        // this object is a service container object
        // then the parameter will bind.
        if($parameter->getType()!==null && isset($containers[$parameter->getType()->getName()])){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName=$parameter->getName();
            $parameterResolve=app()->makeBind($containers[$parameter->getType()->getName()]);
            return [$parameterName=>$parameterResolve];

        }

        return [];
    }


    /**
     * @param $class
     */
    private function getReflectionMethod($class){
        return new \ReflectionMethod($class[0],$class[1]);
    }

    /**
     * @return mixed|void
     */
    private function getContainers(){
        return app()->singleton()->serviceContainer;
    }
}