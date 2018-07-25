<?php

namespace Resta\Authenticate;

trait AuthenticateToken {

    /**
     * @return string
     */
    public function getTokenData(){

        // the absolute params property must be present
        // in the object and the params value must be the builder key.
        if(property_exists($this,'params') and isset($this->params['builder'])){

            // a real token will be generated after
            // you get the first method of the query builder value.
            $authData=$this->params['builder']->first();
            return md5(sha1($authData->id.'__'.time()));
        }

        return null;


    }

}