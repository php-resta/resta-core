<?php

namespace Resta\Authenticate;

trait AuthenticateException {

    /**
     * @return void|mixed
     */
    public function getExceptionForLoginHttp($http){
        exception()->badMethodCall('Authenticate requests ['.$http['login'].'] as http method');
    }

    /**
     * @return void|mixed
     */
    public function update(){
        exception()->domain('Updating Token for authenticate is missing.');
    }

    /**
     * @return void|mixed
     */
    public function credentials(){
        exception()->domain('credentials fail for authenticate');
    }

    /**
     * @param $exceptionType
     */
    public function exceptionManager($exceptionType){
        return $this->{$exceptionType}();
    }
}