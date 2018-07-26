<?php

namespace Resta\Authenticate\Driver\Eloquent;

use Resta\Authenticate\Driver\BuilderContract;

class UserBuilder extends UserBuilderHelper implements BuilderContract {

    /**
     * @var $app
     */
    protected $auth;

    /**
     * UserBuilder constructor.
     * @param $auth
     */
    public function __construct($auth) {

        //authenticate instance
        $this->auth=$auth;

        parent::__construct();
    }

    /**
     * @param $credentials
     * @return mixed|void
     */
    public function login($credentials){

        // using the driver object we write the query builder statement.
        // we do the values of the query with the credentials that are sent.
        $query=$this->setQuery($credentials);

        // with query we bind the returned values to the params property of the auth object.
        // and so the auth object will make a final return with these values.
        $this->auth->params['type']        = 'login';
        $this->auth->params['builder']     = $query;
        $this->auth->params['data']        = $query->first();
        $this->auth->params['status']      = $query->count();

        // when the query succeeds,
        // we update the token value.
        $this->updateToken();
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
        $this->auth->params['type']        = 'check';
        $this->auth->params['builder']     = $query;
        $this->auth->params['data']        = $query->first();
        $this->auth->params['status']      = $query->count();

    }
}
