<?php

namespace Resta\Foundation;

use Resta\Response\ResponseOutManager;
use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

class ApplicationProvider
{
    /**
     * @var $app ApplicationContracts|ContainerContracts
     */
    public $app;

    /**
     * Application Constructor
     *
     * @param $app ApplicationContracts|ContainerContracts
     */
    public function __construct(ApplicationContracts $app)
    {
        $this->app = $app;
    }
}