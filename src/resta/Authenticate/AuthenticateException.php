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
}