<?php

namespace Resta\Authenticate;

trait AuthenticateException {

    /**
     * @return void|mixed
     */
    public function getExceptionForHttp($http){
        exception()->badMethodCall('Authenticate requests ['.$http.'] as http method');
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

    public function logoutException(){
        exception()->domain('You are already logout');
    }

    public function tokenException(){
        exception()->domain('Your token is missing for authenticate process');
    }

    /**
     * @return void|mixed
     */
    public function update(){
        exception()->domain('Updating Token for authenticate is missing.');
    }
}