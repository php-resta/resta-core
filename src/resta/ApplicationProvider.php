<?php

namespace Resta;

use Resta\Utils;

class ApplicationProvider {

    /**
     * @var $app \Resta\Contracts\ApplicationContracts
     */
    public $app;

    /**
     * constructor.
     * @param $app \Resta\Contracts\ApplicationContracts
     */
    public function __construct($app)
    {
        //application object
        $this->app=$app;
    }

    /**
     * SymfonyRequest constructor.
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function request()
    {
        //symfony request
        return $this->app->kernel()->request;
    }

    /**
     * @method $class
     * @param $class
     * @return mixed
     */
    public function makeBind($class){

        return Utils::makeBind($class,$this->providerBinding());
    }

    /**
     * @method providerBinding
     * @return mixed
     */
    public function providerBinding(){

        return $this->app->applicationProviderBinding($this->app);
    }

    /**
     * @method getStatus
     * @return mixed
     */
    public function getStatus(){

        return $this->app->kernel()->responseStatus;
    }

    /**
     * @method getSuccess
     * @return mixed
     */
    public function getSuccess(){

        return $this->app->kernel()->responseSuccess;
    }
}