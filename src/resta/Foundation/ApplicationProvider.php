<?php

namespace Resta\Foundation;

use Resta\Response\ResponseOutManager;
use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

class ApplicationProvider
{
    /**
     * @var $app \Resta\Contracts\ApplicationContracts|ContainerContracts
     */
    public $app;

    /**
     * constructor.
     * @param $app \Resta\Contracts\ApplicationContracts|ContainerContracts
     */
    public function __construct(ApplicationContracts $app)
    {
        /**
         * @var $app \Resta\Contracts\ApplicationContracts|ContainerContracts
         */
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function httpMethod()
    {
        return strtolower(core()->httpMethod);
    }

    /**
     * @return mixed
     */
    public function routeParameters()
    {
        return core()->routeParameters;
    }

    /**
     * @return ResponseOutManager
     */
    public function response()
    {
        $object=debug_backtrace()[2]['object'];
        return new ResponseOutManager($object);
    }
}