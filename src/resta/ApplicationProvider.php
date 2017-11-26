<?php

namespace Resta;

use Resta\Utils;

class ApplicationProvider {

    /**
     * @var $app \Resta\Contracts\ApplicationContracts
     */
    public $app;

    /**
     * SymfonyRequest constructor.
     * @param $app
     */
    public function __construct($app)
    {
        //application object
        $this->app=$app;
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
    private function providerBinding(){

        return $this->app->applicationProviderBinding($this->app);
    }
}