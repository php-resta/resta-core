<?php

namespace Resta\Foundation;

use Resta\Support\Utils;
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
     * @var $url
     */
    public $url;

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

        //url object assign
        $this->url();
    }

    /**
     * Application Kernel.
     * @return mixed
     */
    public function app()
    {
        //symfony request
        return $this->app->kernel();
    }

    /**
     * @method url
     * @return mixed
     */
    public function url()
    {
        $this->url = [];

        if(isset($this->app()->url)){

            //we assign the url object to the global kernel url object
            //so that it can be read anywhere in our route file.
            $this->url = core()->url;
        }
    }
    
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->app()->responseStatus;
    }

    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->app()->responseSuccess;
    }

    /**
     * @return string
     */
    public function httpMethod()
    {
        return strtolower($this->app()->httpMethod);
    }

    /**
     * @return mixed
     */
    public function routeParameters()
    {
        return $this->app()->routeParameters;
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