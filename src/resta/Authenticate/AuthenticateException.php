<?php

namespace Resta\Authenticate;

trait AuthenticateException {

    /**
     * @return void|mixed
     */
    public function getExceptionForLoginHttp(){

        $getHttp=$this->getHttp();

        if(!is_array($getHttp)) $getHttp=[];

        if(isset($getHttp['login']) and $getHttp['login']!==appInstance()->httpMethod()){
            exception()->badMethodCall('Authenticate requests ['.$getHttp['login'].'] as http method');
        }
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