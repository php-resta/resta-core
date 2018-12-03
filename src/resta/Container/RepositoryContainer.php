<?php

namespace Resta\Container;

class RepositoryContainer
{
    /**
     * @param $parameter \ReflectionParameter
     * @param $param
     * @return mixed
     */
    public function handle($parameter,$param)
    {
        // We will use a custom bind for the repository classes
        // and bind the repository contract with the repository adapter class.
        $parameterName  = $parameter->getType()->getName();
        $repository     = app()->namespace()->repository();

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
}