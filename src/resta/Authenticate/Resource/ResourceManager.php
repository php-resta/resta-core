<?php

namespace Resta\Authenticate\Resource;

class ResourceManager
{
    /**
     * @var $auth
     */
    protected $auth;

    /**
     * @var $driverBuilderInstance
     */
    protected $driverBuilderInstance;

    /**
     * ResourceManager constructor.
     * @param $auth
     */
    public function __construct($auth)
    {
        //authenticate instance
        $this->auth=$auth;

        // for the builder, we get the namespace value from the auth object.
        // this namespace will take us to the query builder application.
        $driverBuilder=$this->auth->getDriverBuilderNamespace();

        // we get the instance value of
        // the imported builder object.
        $this->driverBuilderInstance=new $driverBuilder($auth);
    }
}