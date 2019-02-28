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
     * Application Constructor
     *
     * @param $app \Resta\Contracts\ApplicationContracts|ContainerContracts
     */
    public function __construct(ApplicationContracts $app)
    {
        /**
         * @var $app \Resta\Contracts\ApplicationContracts|ContainerContracts
         */
        $this->app = $app;
    }
}