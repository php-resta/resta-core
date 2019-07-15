<?php

namespace Resta\Authenticate\Resource;

class AuthCheckManager extends ResourceManager
{
    /**
     * @var string
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
        $this->token = $token;

        //query check
        $this->checkProcess();
    }

    /**
     * @return void|mixed
     */
    public function checkProcess()
    {
        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        $this->driverBuilderInstance->check($this->token);
    }
}