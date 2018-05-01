<?php

namespace Resta;

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

        //
        $param=$this->reflectionMethodParameters($class,$param);

        //
        $params=(object)['class'=>$class,'param'=>$param];
        return call_user_func_array($callback,[$params]);
    }

    /**
     * @param $class
     * @param $param
     */
    private function reflectionMethodParameters($class,$param){
        
        //
        $reflection=$this->getReflectionMethod($class);
        $parameters=$reflection->getParameters();

        $containers=$this->getContainers();

        //
        foreach ($parameters as $parameter){

            if($parameter->getType()!==null && isset($containers[$parameter->getType()->getName()])){

                //
                $parameterName=$parameter->getName();
                $parameterResolve=app()->makeBind($containers[$parameter->getType()->getName()]);
                $param=array_merge($param,[$parameterName=>$parameterResolve]);

            }

            //
            if($parameter->getType()!==null){
                $param=$this->restaContainerGrace($parameter,$param);
            }

            if($parameter->getName()=="route"){
                $this->routeParameterProcess($parameter->getDefaultValue());
            }

        }

        return $param;
    }

    /**
     * @param $routeParams
     */
    private function routeParameterProcess($routeParams){

        $method=strtolower(app()->singleton()->url['method']);
        app()->singleton()->bound->register('serviceConf','routeParameters',[$method=>$routeParams]);
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

        $parameterName  = $parameter->getType()->getName();
        $repository     = app()->namespace()->optionalRepository();

        $parameterNameWord  = str_replace('\\','',$parameterName);
        $repositoryWord     = str_replace('\\','',$repository);


        if(preg_match('@'.$repositoryWord.'@is',$parameterNameWord)){

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