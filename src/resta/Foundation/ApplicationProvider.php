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
     * @param null $param
     * @param null $default
     * @return null
     */
    public function headers($param=null,$default=null)
    {
        $list=[];

        //We only get the objects in the list name to match the header objects
        //that come with the request path to the objects sent by the client
        foreach (request()->headers->all() as $key=>$value) {
            $list[$key]=$value;
        }

        //return header list
        return ($param===null) ? $list : (isset($list[$param]) ? $list[$param][0] : $default);
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
     * @param $class
     * @param array $bind
     * @return mixed|null
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function makeBind($class,$bind=array())
    {
        return Utils::makeBind($class,$this->providerBinding($bind));
    }

    /**
     * @param array $bind
     * @return mixed
     */
    public function providerBinding($bind=array())
    {
        return $this->app->applicationProviderBinding($this->app,$bind);
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