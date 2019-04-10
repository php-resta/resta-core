<?php

namespace Resta\Foundation;

use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

abstract class ApplicationProvider
{
    /**
     * application instance
     *
     * @var $app ApplicationContracts|ContainerContracts
     */
    protected $app;

    /**
     * Application Constructor
     *
     * @param $app ApplicationContracts|ContainerContracts
     */
    public function __construct(ApplicationContracts $app)
    {
        $this->app = $app;
    }

    /**
     * get application instance
     *
     * @return ApplicationContracts|ContainerContracts
     */
    public function app()
    {
        return $this->app;
    }
}