<?php

namespace Resta\Response;

class ResponseOutManager  {

    /**
     * @var $app
     */
    private $app;

    /**
     * ResponseOutManager constructor.
     * @param $app
     */
    public function __construct($app) {
        $this->app=$app;
    }

    /**
     * @method json
     * @return json
     */
    public function json(){
        $this->app->response='json';
    }

    /**
     * @method xml
     * @return void
     */
    public function xml(){
        $this->app->response='xml';
    }
}