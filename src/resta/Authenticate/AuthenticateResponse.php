<?php

namespace Resta\Authenticate;

trait AuthenticateResponse {

    /**
     * @var array $params
     */
    public $params=[];

    /**
     * @return array
     */
    protected function getResult(){

        $result= [];

        // if the status value is true,
        // we send output generated from the token value.
        if(isset($this->params['status']) && $this->params['status']){
            $result['message']                  = 'token success';
            $result['token']                    = $this->params['token'];

            //we send the result value.
            return $result;
        }

        // if the status is unsuccessful,
        // the direct exception will be thrown away.
        exception()->domain('credentials fail for authenticate');


    }

}