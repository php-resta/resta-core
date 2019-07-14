<?php

namespace Resta\Authenticate\Resource;

use Resta\Authenticate\AuthenticateProvider;
use Resta\Authenticate\Driver\BuilderContract;

abstract class ResourceManager
{
    /**
     * @var AuthenticateProvider
     */
    protected $auth;

    /**
     * @var null|object
     */
    protected $driverBuilderInstance;

    /**
     * ResourceManager constructor.
     * @param $auth
     */
    public function __construct($auth)
    {
        // if the auth value does not carry
        // the authenticateProvider instance value, an exception is thrown.
        if(!$auth instanceof AuthenticateProvider){
            exception()->runtime('AuthenticateProvider instance is not valid');
        }

        //authenticate instance
        $this->auth = $auth;

        // for the builder, we get the namespace value from the auth object.
        // this namespace will take us to the query builder application.
        $driverBuilder = $this->auth->getDriverBuilderNamespace();

        // we get the instance value of
        // the imported builder object.
        $this->driverBuilderInstance = new $driverBuilder($auth);

        if(!$this->driverBuilderInstance instanceof BuilderContract){
            exception()->runtime($driverBuilder.' is not instance of '.BuilderContract::class);
        }
    }

    /**
     * get auth
     *
     * @return AuthenticateProvider
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * get driver builder instance
     *
     * @return object|null
     */
    public function getDriverBuilderInstance()
    {
        return $this->driverBuilderInstance;
    }
}