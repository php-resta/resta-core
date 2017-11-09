<?php

namespace Resta;

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
}