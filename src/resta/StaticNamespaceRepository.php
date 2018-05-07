<?php

namespace Resta;

use Resta\Contracts\StaticNamespaceContracts;

class StaticNamespaceRepository extends StaticPathRepository implements StaticNamespaceContracts {

    /**
     * @return mixed
     */
    public function optionalException(){
        return Utils::getNamespace($this->appOptionalException());
    }

    /**
     * @return mixed
     */
    public function optionalRepository(){
        return Utils::getNamespace($this->appOptionalRepository());
    }

    /**
     * @return mixed
     */
    public function optionalJob(){
        return Utils::getNamespace($this->appOptionalJob());
    }

    /**
     * @return mixed
     */
    public function optionalSource(){
        return Utils::getNamespace($this->appOptionalSource());
    }

    /**
     * @return mixed
     */
    public function optionalWebservice(){
        return Utils::getNamespace($this->appOptionalWebservice());
    }

    /**
     * @param null $endpoint
     * @param array $bind
     * @return mixed|string
     */
    public function call($endpoint=null,$bind=array()){

        $call=Utils::getNamespace($this->appCall());

        if($endpoint===null) return $call;

        $endpointCall=$call.'\\'.ucfirst($endpoint).'\\'.ucfirst($endpoint).'Service';

        return app()->makeBind($endpointCall,$bind);

    }

    /**
     * @return mixed
     */
    public function middleware(){
        return Utils::getNamespace($this->appMiddleware());
    }

    /**
     * @return mixed
     */
    public function model(){
        return Utils::getNamespace($this->appModel());
    }

    /**
     * @return mixed
     */
    public function migration(){
        return Utils::getNamespace($this->appMigration());
    }

    /**
     * @return mixed
     */
    public function config(){
        return Utils::getNamespace($this->appConfig());
    }

    /**
     * @return mixed
     */
    public function kernel(){
        return Utils::getNamespace($this->appKernel());
    }

    /**
     * @param null $command
     * @param array $argument
     * @return mixed|string
     */
    public function command($command=null,$argument=array()){

        $commandNamespace=Utils::getNamespace($this->appCommand());

        if($command===null) return $commandNamespace;

        $commandCall=$commandNamespace.'\\'.ucfirst($command);

        $defaultArgument=['project'=>app,'commandCall'=>true];
        $argumentList=array_merge($defaultArgument,$argument);

        return new $commandCall($argumentList,app());
    }

}