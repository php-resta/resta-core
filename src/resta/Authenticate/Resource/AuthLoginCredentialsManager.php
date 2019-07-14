<?php

namespace Resta\Authenticate\Resource;

use Resta\Authenticate\AuthenticateRequest;

class AuthLoginCredentialsManager
{
    /**
     * @var null|array
     */
    protected $credentials;

    /**
     * @var null|string
     */
    protected $credentialHash;

    /**
     * @var null|object
     */
    protected $request;

    /**
     * @var null|AuthLoginManager
     */
    protected $manager;

    /**
     * AuthLoginCredentialsManager constructor.
     * @param $credentials
     * @param null|AuthLoginManager $manager
     */
    public function __construct($credentials,$manager)
    {
        //get credentials as default
        $this->credentials = $credentials;

        //set manager for auth login
        $this->manager = $manager;

        //get credential hash
        $this->setCredentialHash();

        if($this->manager->getUsing()===false){

            // the request object will help you process
            // the credentials and get them correctly.
            $this->request = $this->getRequest();
        }

        //request handle
        $this->handle();
    }

    /**
     * get credentials
     *
     * @return mixed
     */
    public function get()
    {
        //get credentials
        return $this->credentials;
    }

    /**
     * get credential hash
     *
     * @return null|string
     */
    public function getCredentialHash()
    {
        //get credential hash
        return $this->credentialHash;
    }

    /**
     * get manager
     *
     * @return AuthLoginManager|null
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * get request for authenticate
     *
     * @return mixed
     */
    public function getRequest()
    {
        $request = $this->manager->getAuth()->getRequest();

        if($request=='Default'){
            return new AuthenticateRequest($this);
        }

        return new $request($this->credentials);
    }

    /**
     * credential handle
     *
     * @return void|mixed
     */
    private function handle()
    {
        // with the request object we get
        // the credentials values through the all method.
        $this->credentials = (is_null($this->request)) ? $this->get()
            : $this->request->credentials($this->credentials);
    }

    /**
     * set credential hash
     *
     * @return void|mixed
     */
    private function setCredentialHash()
    {
        //set credential hash
        if(count($this->credentials)){
            $this->credentialHash = md5(sha1(implode("|",$this->credentials)));
        }
    }
}