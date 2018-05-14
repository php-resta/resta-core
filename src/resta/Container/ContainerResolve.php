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
        $reflection=$this->getReflectionMethod($class);
        $parameters=$reflection->getParameters();

        //service container objects.
        $containers=$this->getContainers();

        // we group the parameters into type and
        // name and bind them with the necessary logic.
        foreach ($parameters as $parameter){

            // if the parameter is an object and
            // this object is a service container object
            // then the parameter will bind.
            if($parameter->getType()!==null && isset($containers[$parameter->getType()->getName()])){

                // Unpack the container object and
                // bind it to the param variable.
                $parameterName=$parameter->getName();
                $parameterResolve=app()->makeBind($containers[$parameter->getType()->getName()]);
                $param=array_merge($param,[$parameterName=>$parameterResolve]);

            }

            // if the parameter is an object
            // but not a container object.
            if($parameter->getType()!==null){

                // we do some useful logic bind for user benefit.
                $param=$this->restaContainerGrace($parameter,$param);
            }

            // If the parameter contains a route variable.
            if($parameter->getName()=="route"){

                // We do a custom bind for the route
                $param=$this->routeParameterProcess($parameter->getDefaultValue(),$param);
            }

        }

        return $param;
    }

    /**
     * @param $routeParams
     * @param $param
     */
    private function routeParameterProcess($routeParams,$param){

        // We record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method=strtolower(app()->singleton()->url['method']);
        app()->singleton()->bound->register('serviceConf','routeParameters',[$method=>$routeParams]);

        $param['route']=route();

        $routeList=[];
        foreach ($routeParams as $routeKey=>$routeVal){
            if(!isset($param['route'][$routeVal])){
                $routeList[$routeVal]=null;
            }
            else{
                $routeList[$routeVal]=strtolower($param['route'][$routeVal]);
            }
        }

        $param['route']=$routeList;

        return $param;
    }

    /**
     * @param $parameter
     * @param $param
     * @return mixed
     */
    private function restaContainerGrace($parameter,$param){
        return $this->checkContainerForRepository($parameter,$param);
    }

    /**
     * @param $parameter
     * @param $param
     */
    private function checkContainerForRepository($parameter,$param){

        // We will use a custom bind for the repository classes
        // and bind the repository contract with the repository adapter class.
        $parameterName  = $parameter->getType()->getName();
        $repository     = app()->namespace()->optionalRepository();

        $parameterNameWord  = str_replace('\\','',$parameterName);
        $repositoryWord     = str_replace('\\','',$repository);


        // if the submitted contract matches the repository class.
        if(preg_match('@'.$repositoryWord.'@is',$parameterNameWord)){

            //we bind the contract as an adapter
            $repositoryName=str_replace('Contract','',$parameter->getName());
            $getRepositoryAdapter=\application::repository($repositoryName,true);

            $param[$parameter->getName()]=app()->makeBind($getRepositoryAdapter)->adapter();
        }

        return $param;


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