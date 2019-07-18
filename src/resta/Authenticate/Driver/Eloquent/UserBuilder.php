<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\AuthenticateProvider;
use Resta\Authenticate\Driver\BuilderContract;
use Resta\Authenticate\Resource\AuthUserManager;
use Resta\Authenticate\Driver\BuilderParamGenerator;

class UserBuilder extends UserBuilderHelper implements BuilderContract
{
    //get param generator
    use BuilderParamGenerator;

    /**
     * @var AuthenticateProvider
     */
    protected $auth;

    /**
     * UserBuilder constructor.
     * @param $auth \Resta\Authenticate\AuthenticateProvider
     */
    public function __construct($auth)
    {
        //authenticate instance
        $this->auth = $auth;

        parent::__construct();
    }

    /**
     * get all device tokens for user
     *
     * @param AuthUserManager $manager
     * @return mixed
     */
    public function allDeviceTokens($manager)
    {
        return $this->allDeviceTokenQuery($manager);
    }

    /**
     * check builder
     *
     * @param $token
     */
    public function check($token)
    {
        // using the driver object we write the query builder statement.
        // we do the values of the query with the token that are sent.
        $query = $this->checkQuery($token);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('check',$query);
    }

    /**
     * login builder
     *
     * @param \Resta\Authenticate\Resource\AuthLoginCredentialsManager $credentials
     * @return mixed|void
     */
    public function login($credentials)
    {
        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        if(!is_null($provider = $this->auth->provider('login'))){
            $query = $provider($credentials->get());
        }
        else{
            $query = $this->setQuery($credentials);
        }

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('login',$query);

        // we assign the credential hash value
        // to the global of the authenticate object.
        $this->auth->credentialHash = $credentials->getCredentialHash();

        // when the query succeeds,
        // we update the token value.
        $this->updateToken();

        if(isset($this->auth->params['authToken'])){
            $this->saveDeviceToken();
        }
    }

    /**
     * logout builder
     *
     * @param $token
     * @return mixed|void
     */
    public function logout($token)
    {
        // using the driver object we write the query builder statement.
        // we do the values of the query with the token that are sent.
        $query = $this->logoutQuery($token);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('logout',$query);

        //token updating as null
        if(isset($this->auth->params['authToken'])){
            if(!$this->deleteDeviceToken()){
                $this->auth->params['status'] = 0;
                $this->auth->params['exception'] = 'logoutInternal';
                return false;
            }
        }

        if($this->auth->params['status']===0){
            $this->auth->params['exception'] = 'logoutException';
        }
    }

    /**
     * get user process
     *
     * @param AuthUserManager $manager
     * @return mixed
     */
    public function userProcess($manager)
    {
        return $this->userProcessQuery($manager);
    }
}
