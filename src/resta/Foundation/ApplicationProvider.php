<?php

namespace Resta\Foundation;

use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

abstract class ApplicationProvider
{
    /**
     * application instance
     *
     * @var ApplicationContracts $app
     */
    protected $app;

    /**
     * Application Constructor
     *
     * @param ApplicationContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        $this->app = $app;
    }

    /**
     * get application instance
     *
     * @return ApplicationContracts
     */
    public function app()
    {
        return $this->app;
    }
}