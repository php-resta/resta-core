<?php

namespace Resta\Authenticate\Resource;

class AuthLoginManager extends ResourceManager
{
    /**
     * @var mixed|array
     */
    protected $credentials;

    /**
     * @var bool
     */
    protected $using = false;

    /**
     * AuthLoginManager constructor.
     * @param $credentials
     * @param $auth
     */
    public function __construct($credentials,$auth)
    {
        parent::__construct($auth);

        // where the control mechanism of the credentials
        // values received from the user-defined yada config setting is made.
        $this->credentials = new AuthLoginCredentialsManager($this->getCredentials($credentials),$this);

        //query login
        $this->loginProcess();
    }

    /**
     * get credentials for authenticate
     * 
     * @param $credentials
     */
    private function getCredentials($credentials)
    {
        // if the user is not going to use the config setting,
        // then in this case it can attempt to login by sending parameters
        // as an array to the login method.
        if(is_array($credentials) && count($credentials)){

            $this->using = true;
            return $credentials;
        }

        //get credentials as default
        return $this->auth->getCredentials();
    }

    /**
     * get using
     *
     * @return bool
     */
    public function getUsing()
    {
        return $this->using;
    }

    /**
     * @return void|mixed
     */
    public function loginProcess()
    {
        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        return $this->driverBuilderInstance->login($this->credentials);
    }
}