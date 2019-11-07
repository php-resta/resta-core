<?php

namespace Resta\Authenticate;

use Resta\Authenticate\Resource\AuthUserManager;
use Resta\Authenticate\Resource\AuthCheckManager;
use Resta\Authenticate\Resource\AuthLoginManager;
use Resta\Authenticate\Resource\AuthLogoutManager;

class AuthenticateProvider extends ConfigProvider implements AuthenticateContract
{
    //get auth response,auth exception,auth token and auth basic
    use AuthenticateResponse,AuthenticateException,AuthenticateToken,AuthenticateBasic;

    /**
     * @var string
     */
    protected $guard = 'default';

    /**
     * get all device tokens for authenticate
     *
     * @return mixed|null
     */
    public function allDeviceTokens()
    {
        // we obtain the data value obtained via
        // authenticate availability with the help of callback object.
        return $this->checkParamsViaAvailability(function(){
            return (new AuthUserManager($this->currentDeviceToken(),$this))->allDeviceTokens();
        });
    }

    /**
     * check if the authenticated of user
     *
     * @return bool
     */
    public function check()
    {
        // header to determine whether
        // the token value is present and return a callback.
        return $this->checkTokenViaHeaders(function($token){

            // we send the user-supplied token value
            // to the authCheckManager object.
            new AuthCheckManager($this,$token);

            // as a result we send output according to
            // the boolean value from the checkResult method.
            return $this->getCheckResult();
        });
    }

    /**
     * get current device token the authenticated user
     *
     * @return mixed
     */
    public function currentDeviceToken()
    {
        // we obtain the data value obtained via
        // authenticate availability with the help of callback object.
        return $this->checkParamsViaAvailability('data',function($data){
            return $data;
        });
    }

    /**
     * authenticate guard adapter
     *
     * @param $guard
     * @return $this|mixed
     *
     */
    public function guard($guard)
    {
        $this->guard = $guard;

        $this->setAuthenticateNeeds();

        return $this;
    }

    /**
     * login for authenticate
     *
     * @param null|array $credentials
     * @param bool $objectReturn
     * @return $this|mixed
     */
    public function login($credentials=null,$objectReturn=false)
    {
        // we will determine whether
        // the http path is correct for this method.
        $this->checkProcessHttpMethod('login');

        // we invoke the login manager and the properties
        // that this class creates will inform us about user input.
        $loginManager = new AuthLoginManager($credentials,$this);

        // if you want to see the entire login manager object directly,
        // send true to the objectReturn parameter.
        if($objectReturn) return $loginManager;

        // the login value stored in the params property of the login manager object will return a builder object.
        // we will return the value of the login state as a boolean using the count method of this builder object.
        return $this->getResult();

    }

    /**
     * logout the authenticated user
     *
     * @return mixed|void
     */
    public function logout()
    {
        // we will determine whether
        // the http path is correct for this method.
        $this->checkProcessHttpMethod('logout');

        // header to determine whether
        // the token value is present and return a callback.
        return $this->checkTokenViaHeaders(function($token){

            // we send the user-supplied token value
            // to the authCheckManager object.
            new AuthLogoutManager($this,$token);

            // as a result we send output according to
            // the boolean value from the checkResult method.
            return $this->getLogoutResult();
        });
    }

    /**
     * get data of the authenticated user
     *
     * @return mixed
     */
    public function user()
    {
        // we obtain the user value obtained via
        // authenticate availability with the help of callback object.
        return $this->checkParamsViaAvailability(function(){
            return (new AuthUserManager($this->currentDeviceToken(),$this))->userProcess();
        });
    }

    /**
     * get token sent by user
     *
     * @return null|string
     */
    public function getTokenSentByUser()
    {
        //get headers
        $headers = headers();

        //get token key from config
        $tokenKey = $this->getTokenKey();

        return (isset($headers[$tokenKey])) ? $headers[$tokenKey][0] : null;
    }
}
