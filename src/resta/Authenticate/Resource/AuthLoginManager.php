<?php

namespace Resta\Authenticate\Resource;

class AuthLoginManager extends ResourceManager {

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

        parent::__construct($auth);

        // where the control mechanism of the credentials
        // values received from the user-defined yada config setting is made.
        $this->credentials=new AuthLoginCredentialsManager($this->auth->getCredentials());

        //query login
        $this->loginProcess();
    }

    /**
     * @return void|mixed
     */
    private function loginProcess(){

        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        $this->driverBuilderInstance->login($this->credentials->get());
    }
}