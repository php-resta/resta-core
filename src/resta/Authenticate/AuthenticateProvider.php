<?php

namespace Resta\Authenticate;

use Resta\Authenticate\Resource\AuthLoginManager;

class AuthenticateProvider extends ConfigProvider implements AuthenticateContract {

    /**
     * @var array $params
     */
    public $params=[];

    /**
     * @var string
     */
    protected $guard='default';

    /**
     * @return bool
     */
    public function check(){

        return true;
    }

    /**
     * @param $store
     * @return $this
     */
    public function guard($guard) {

        $this->guard=$guard;

        $this->setAuthenticateNeeds();

        return $this;
    }

    /**
     * @return bool
     */
    public function id(){

        return true;
    }

    /**
     * @param array $credentials
     * @param bool $objectReturn
     * @return $this|mixed
     */
    public function login($credentials=array(),$objectReturn=false){

        // we invoke the login manager and the properties
        // that this class creates will inform us about user input.
        $loginManager = new AuthLoginManager($credentials,$this);

        // if you want to see the entire login manager object directly,
        // send true to the objectReturn parameter.
        if($objectReturn) return $loginManager;

        // the login value stored in the params property of the login manager object will return a builder object.
        // we will return the value of the login state as a boolean using the count method of this builder object.
        return ($this->params['loginStatus']) ? true : false;
    }

    /**
     * @return mixed|void
     */
    public function logout(){

        return true;
    }

    /**
     * @return bool
     */
    public function user(){

        return true;
    }

}
