<?php

namespace Resta\Authenticate\Resource;

class AuthLogoutManager extends ResourceManager
{
    /**
     * @var $token
     */
    protected $token;

    /**
     * AuthCheckManager constructor.
     * @param $auth
     * @param $token
     */
    public function __construct($auth,$token)
    {
        parent::__construct($auth);

        //token value received by client
        $this->token=$token;

        //query check
        $this->logoutProcess();
    }

    /**
     * @return void|mixed
     */
    public function logoutProcess()
    {
        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        $this->driverBuilderInstance->logout($this->token);
    }
}