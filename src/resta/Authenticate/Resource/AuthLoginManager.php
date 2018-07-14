<?php

namespace Resta\Authenticate\Resource;

class AuthLoginManager {

    /**
     * @var $auth
     */
    protected $auth;

    /**
     * @var $credentials
     */
    protected $credentials;

    /**
     * AuthLoginManager constructor.
     * @param $credentials
     * @param \Resta\Authenticate\AuthenticateProvider $auth
     */
    public function __construct($credentials,$auth) {

        //authenticate instance
        $this->auth=$auth;

        // where the control mechanism of the credentials
        // values received from the user-defined yada config setting is made.
        $this->credentials=new AuthLoginCredentialsManager($this->auth->getCredentials());

        //query handle
        $this->handle();
    }

    /**
     * @return void|mixed
     */
    public function handle(){

        // for the builder, we get the namespace value from the auth object.
        // this namespace will take us to the query builder application.
        $driverBuilder=$this->auth->getDriverBuilderNamespace();

        // we get the instance value of
        // the imported builder object.
        $driverBuilderInstance=new $driverBuilder();

        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        $driverBuilderInstance->login($this->auth,$this->credentials->get());
    }
}