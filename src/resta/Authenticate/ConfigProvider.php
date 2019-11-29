<?php

namespace Resta\Authenticate;

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
     * ConfigProvider constructor.
     */
    public function __construct()
    {
        $this->config();

        if($this->guard=="default"){
            $this->setAuthenticateNeeds();
        }

        $this->table();

        $this->deviceTokenTable();
    }

    /**
     * get authenticate config values
     *
     * @return void|mixed
     */
    public function config()
    {
        $this->config = config('authenticate');

        if(!is_null($config = $this->provider('configuration'))){
            $this->config['guard'][$this->guard] = $config($this->config['guard'][$this->guard]);
        }

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
        return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\UserBuilder';
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
        return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\User';
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
     * get provider for authenticate
     *
     * @param $key
     * @return mixed|null
     */
    public function provider($key)
    {
        if(app()->has('authenticate.'.$key) && is_callable($provider = app()->get('authenticate.'.$key))){
            return $provider;
        }

        return null;
    }

    /**
     * set authenticate needs
     *
     * @return void|mixed
     */
    protected function setAuthenticateNeeds()
    {
        $this->getDriver();
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

    /**
     * table name for authenticate
     *
     * @return string
     */
    public function table()
    {
        $table = $this->config['guard'][$this->guard]['table'];

        app()->register('authenticateTable',$table);
    }

    /**
     * table name for authenticate
     *
     * @return string
     */
    public function deviceTokenTable()
    {
        if(isset($this->config['guard'][$this->guard]['deviceTokenRegister'])){
            $table = $this->config['guard'][$this->guard]['deviceTokenRegister'];

            app()->register('authenticateDeviceTokenTable',$table);
        }
        else{
            app()->register('authenticateDeviceTokenTable','device_tokens');
        }

    }

}