<?php

namespace Resta\Authenticate\Resource;

use Resta\Authenticate\AuthenticateRequest;

class AuthLoginCredentialsManager {

    /**
     * @var $credentials
     */
    protected $credentials;

    /**
     * @var $request
     */
    protected $request;

    /**
     * @return void|mixed
     */
    public function __construct($credentials){

        //get credentials as default
        $this->credentials=$credentials;

        // the request object will help you process
        // the credentials and get them correctly.
        $this->request=new AuthenticateRequest($this->credentials);

        //request handle
        $this->handle();
    }

    /**
     * @return void|mixed
     */
    private function handle(){

        // with the request object we get
        // the credentials values through the all method.
        $this->credentials=$this->request->credentials($this->credentials);
    }

    /**
     * @return mixed
     */
    public function get(){

        //get credentials
        return $this->credentials;
    }
}