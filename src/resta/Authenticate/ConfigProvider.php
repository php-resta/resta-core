<?php

namespace Resta\Authenticate;

use Store\Services\HttpSession as Session;

class ConfigProvider {

    /**
     * @var string
     */
    protected $driverDefaultNamespace="\Resta\Authenticate\Driver";

    /**
     * @var string $store
     */
    protected $store='session';

    /**
     * @var $config
     */
    protected $config;

    /**
     * @var $driver
     */
    protected $driver;

    /**
     * @var $model
     */
    protected $model;

    /**
     * ConfigProvider constructor.
     */
    public function __construct() {

        $this->config();

        if($this->guard=="default"){
            $this->setAuthenticateNeeds();
        }
    }

    /**
     * @return void|mixed
     */
    public function config(){

        $this->config=config('authenticate');

        return $this->config;
    }

    /**
     * @return string
     */
    public function getCredentials(){

        return $this->config['guard'][$this->guard]['credentials'];
    }

    /**
     * @return string
     */
    public function getDriver(){

        $this->driver=ucfirst($this->config['guard'][$this->guard]['driver']);

        return $this->driver;
    }

    /**
     * @return string
     */
    public function getDriverNamespace(){

        $model=$this->getModel();

        if($model=="Default"){

            return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\User';
        }
    }

    /**
     * @return string
     */
    public function getDriverBuilderNamespace(){

        $model=$this->getModel();

        if($model=="Default"){

            return $this->driverDefaultNamespace.'\\'.$this->getDriver().'\\UserBuilder';
        }
    }

    /**
     * @return string
     */
    public function getModel(){

        $this->model=ucfirst($this->config['guard'][$this->guard]['model']);

        return $this->model;
    }

    /**
     * @return string
     */
    public function getHttp(){

        return $this->config['guard'][$this->guard]['http'];
    }

    /**
     * @return string
     */
    public function getAddToWhere(){

        if(isset($this->config['guard'][$this->guard]['addToWhere'])){
            return $this->config['guard'][$this->guard]['addToWhere'];
        }
        return null;

    }

    /**
     * @return string
     */
    public function getConfigToken(){

        if(isset($this->config['guard'][$this->guard]['token'])){
            return $this->config['guard'][$this->guard]['token'];
        }
        return null;

    }

    /**
     * @return void|mixed
     */
    protected function setAuthenticateNeeds(){

        $this->getDriver();

        $this->getModel();
    }

    /**
     * @param $store
     * @return $this
     */
    public function store($store) {

        $this->store=$store;

        return $this;
    }

}