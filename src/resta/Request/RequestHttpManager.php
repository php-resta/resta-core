<?php

namespace Resta\Request;

use Resta\Contracts\ApplicationContracts;
use Resta\Foundation\ApplicationProvider;

class RequestHttpManager extends ApplicationProvider
{
    /**
     * @var $method
     */
    protected $method;

    /**
     * RequestHttpManager constructor.
     *
     * @param ApplicationContracts $app
     */
    public function __construct(ApplicationContracts $app)
    {
        parent::__construct($app);

        $this->method = httpMethod();
    }

    /**
     * http data resolve
     *
     * @return mixed
     */
    public function resolve()
    {
        $inputs = $this->app->get($this->method);

        return $inputs ?: json_decode($this->app['request']->getContent());
    }

    /**
     * get http method
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

}