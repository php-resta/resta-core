<?php

namespace Resta\Authenticate;

use Store\Services\HttpSession as Session;

class ConfigProvider
{
    /**
     * @var string
     */
    protected $driverDefaultNamespace = "\Resta\Authenticate\Driver";

    /**
     * @var string $store
     */
    protected $store = 'session';

    /**
     * @var null|mixed
     */
    protected $config;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var null|mixed
     */
    protected $model;

    /**
     * ConfigProvider constructor.
     */
    public function __construct()
    {
        $this->config();

        if($this->guard=="default"){
            $this->setAuthenticateNeeds();
        }
    }

    /**
     * get authenticate config values
     *
     * @return void|mixed
     */
    public function config()
    {
        $this->config = config('authenticate');

        return $this->config;
    }

    /**
     * auth client query
     *
     * @return string
     */
    public function getAddToWhere()
    {
        if(isset($this->config['guard'][$this->guard]['addToWhere'])){
            return $this->config['guard'][$this->guard]['addToWhere'];
        }
        return null;
    }

    /**
     * get config token
     *
     * @return string
     */
    public function getConfigToken()
    {
        if(isset($this->config['guard'][$this->guard]['token'])){
            return $this->config['guard'][$this->guard]['token'];
        }
        return null;
    }

    /**
     * get credentials
     *
     * @return string
     */
    public function getCredentials()
    {
        return $this->config['guard'][$this->guard]['credentials'];
    }

    /**
     * get driver builder namespace
     *
     * @return string
     */
    public function getDriverBuilderNamespace()
    {
        $this->getModel();

        if($this->model=="Default"){

            return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\UserBuilder';
        }

        return $this->model;
    }

    /**
     * get driver for authenticate
     *
     * @return string
     */
    public function getDriver()
    {
        return 'Eloquent';
    }

    /**
     * get driver namespace
     *
     * @return string
     */
    public function getDriverNamespace()
    {
        $this->getModel();

        if($this->model=="Default"){

            return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\User';
        }

        return $this->model;
    }

    /**
     * get expire for authenticate
     * 
     * @return mixed
     */
    public function getExpire()
    {
        return $this->config['guard'][$this->guard]['expire'];
    }

    /**
     * get http for authenticate
     *
     * @return string
     */
    public function getHttp()
    {
        return $this->config['guard'][$this->guard]['http'];
    }

    /**
     * get model for authenticate
     *
     * @return string
     */
    public function getModel()
    {
        $this->model = ucfirst($this->config['guard'][$this->guard]['model']);

        return $this->model;
    }

    /**
     * get request for authenticate
     *
     * @return string
     */
    public function getRequest()
    {
        return ucfirst($this->config['guard'][$this->guard]['request']);
    }

    /**
     * get token key for authenticate
     *
     * @return string
     */
    public function getTokenKey()
    {
        return $this->config['guard'][$this->guard]['key'];
    }

    /**
     * set authenticate needs
     *
     * @return void|mixed
     */
    protected function setAuthenticateNeeds()
    {
        $this->getDriver();

        $this->getModel();
    }

    /**
     * store for authenticate
     *
     * @param $store
     * @return $this
     */
    public function store($store)
    {
        $this->store = $store;

        return $this;
    }

}