<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Driver\BuilderContract;
use Resta\Authenticate\Driver\BuilderParamGenerator;

class UserBuilder extends UserBuilderHelper implements BuilderContract {

    //get param generator
    use BuilderParamGenerator;

    /**
     * @var $app
     */
    protected $auth;

    /**
     * UserBuilder constructor.
     * @param $auth \Resta\Authenticate\AuthenticateProvider
     */
    public function __construct($auth) {

        //authenticate instance
        $this->auth=$auth;

        parent::__construct();
    }

    /**
     * @param $token
     */
    public function check($token){

        // using the driver object we write the query builder statement.
        // we do the values of the query with the token that are sent.
        $query=$this->checkQuery($token);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('check',$query);

    }

    /**
     * @param $credentials \Resta\Authenticate\Resource\AuthLoginCredentialsManager
     * @return mixed|void
     */
    public function login($credentials){

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        $query=$this->setQuery($credentials);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('login',$query);

        // we assign the credential hash value
        // to the global of the authenticate object.
        $this->auth->credentialHash=$credentials->getCredentialHash();

        // when the query succeeds,
        // we update the token value.
        $this->updateToken();
    }

    /**
     * @param $token
     */
    public function logout($token){

        // using the driver object we write the query builder statement.
        // we do the values of the query with the token that are sent.
        $query=$this->logoutQuery($token);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->paramValues('logout',$query);

        //token updating as null
        $this->updateToken(md5(time()));

    }
}
