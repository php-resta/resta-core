<?php

namespace Resta\Response;

class ResponseOutManager
{
    /**
     * @var $app
     */
    private $app;

    /**
     * ResponseOutManager constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app=$app;
    }

    /**
     * @return void
     */
    public function json()
    {
        $this->app->response = 'json';
    }

    /**
     * @return void
     */
    public function xml()
    {
        $this->app->response = 'xml';
    }
}