<?php

namespace Resta\Container;

use Resta\Support\Utils;
use Resta\Support\ReflectionProcess;
use Resta\Foundation\ApplicationProvider;

class ContainerResolve extends ApplicationProvider
{
    /**
     * @param $class
     * @param $param
     * @param callable $callback
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function call($class,$param,callable $callback)
    {
        // We use the reflection class to solve
        // the parameters of the class's methods.
        $param = $this->reflectionMethodParameters($class,$param);

        // as a result
        // we return the resolved class to the callback class
        $params = (object)['class'=>$class,'param'=>$param];
        return call_user_func_array($callback,[$params]);
    }

    /**
     * @param $containers
     * @param $parameter
     * @return array
     */
    private function checkParameterForContainer($containers,$parameter)
    {
        // if the parameter is an object and
        // this object is a service container object
        // then the parameter will bind.
        if($parameter->getType()!==null && isset($containers[$parameter->getType()->getName()])){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();
            $parameterResolve = app()->resolve($containers[$parameter->getType()->getName()]);

            //return result for parameter of the container
            return [$parameterName=>$parameterResolve];
        }

        if($parameter->getType()!== NULL && Utils::isNamespaceExists($parameter->getType()->getName())){

            // Unpack the container object and
            // bind it to the param variable.
            $parameterName = $parameter->getName();
            $parameterResolve = app()->resolve($parameter->getType()->getName());

            //return result for parameter of the container
            return [$parameterName=>$parameterResolve];
        }

        return [];

    }

    /**
     * @param $class
     * @return mixed
     */
    private function getReflectionMethod($class)
    {
        [$class,$method] = [$class[0],$class[1]];

        return $this->app['reflection']($class)->reflectionMethodParams($method);
    }

    /**
     * @param $class
     * @param $param
     * @return mixed
     *
     * @throws \ReflectionException
     */
    private function reflectionMethodParameters($class,$param)
    {
        $containers = [];

        //get service container objects.
        if(isset($this->app['serviceContainer'])){
            $containers = $this->app['serviceContainer'];
        }

        // With the reflection class we get the method.
        // and then we get the parameters in array.
        $reflection = $this->getReflectionMethod($class);
        $parameters = $reflection->parameters;

        // we group the parameters into type and
        // name and bind them with the necessary logic.
        foreach ($parameters as $parameter){

            // if the parameter is an object and
            // this object is a service container object
            // then the parameter will bind.
            $checkParameterForContainer = $this->checkParameterForContainer($containers,$parameter);
            $paramMerge = array_merge($param,$checkParameterForContainer);

            // we do some useful logic bind for user benefit.
            $param = app()->resolve(GraceContainer::class,[
                'reflection' => $reflection->reflection
            ])->graceContainerBuilder($parameter,$paramMerge);

        }

        return $param;
    }
}