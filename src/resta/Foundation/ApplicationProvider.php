<?php

namespace Resta\Foundation;

use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

abstract class ApplicationProvider
{
    /**
     * application instance
     *
     * @var $app
     */
    protected $app;

    /**
     * Application Constructor
     *
     * @param ApplicationContracts|ContainerContracts $app
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