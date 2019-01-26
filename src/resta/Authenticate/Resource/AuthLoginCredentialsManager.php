<?php

namespace Resta\Authenticate\Resource;

use Resta\Authenticate\AuthenticateRequest;

class AuthLoginCredentialsManager
{
    /**
     * @var $credentials
     */
    protected $credentials;

    /**
     * @var null
     */
    protected $credentialHash=null;

    /**
     * @var $request
     */
    protected $request=null;

    /**
     * AuthLoginCredentialsManager constructor.
     * @param $credentials
     * @param bool $using
     */
    public function __construct($credentials,$using=false)
    {
        //get credentials as default
        $this->credentials=$credentials;

        //get credential hash
        $this->setCredentialHash();

        if($using===false){

            // the request object will help you process
            // the credentials and get them correctly.
            $this->request=new AuthenticateRequest($this->credentials);
        }

        //request handle
        $this->handle();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        //get credentials
        return $this->credentials;
    }

    /**
     * @return null
     */
    public function getCredentialHash()
    {
        //get credential hash
        return $this->credentialHash;
    }

    /**
     * @return void|mixed
     */
    private function handle()
    {
        // with the request object we get
        // the credentials values through the all method.
        $this->credentials=($this->request===null) ? $this->get() : $this->request->credentials($this->credentials);
    }

    /**
     * @return void|mixed
     */
    private function setCredentialHash()
    {
        //set credential hash
        if(count($this->credentials)){
            $this->credentialHash=md5(sha1(implode("|",$this->credentials)));
        }
    }
}