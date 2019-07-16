<?php

namespace Resta\Authenticate\Resource;

use Resta\Authenticate\AuthenticateProvider;

class AuthUserManager extends ResourceManager
{
    /**
     * AuthUserManager constructor.
     * @param $deviceTokenId
     * @param AuthenticateProvider $auth
     */
    public function __construct($deviceTokenId,$auth)
    {
        parent::__construct($auth);
        
        $this->auth->params['userId'] = $deviceTokenId['user_id'];
    }

    /**
     * get all device tokens for user
     * 
     * @return void|mixed
     */
    public function allDeviceTokens()
    {
        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        return $this->driverBuilderInstance->allDeviceTokens($this);
    }

    /**
     * get user process
     * 
     * @return void|mixed
     */
    public function userProcess()
    {
        // Finally, we attempt to login the user by running
        // the login method of the builder object.
        return $this->driverBuilderInstance->userProcess($this);
    }
}