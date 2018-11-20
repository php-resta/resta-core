<?php

namespace Resta;

use Resta\Response\ResponseOutManager;
use Resta\Contracts\ContainerContracts;
use Resta\Contracts\ApplicationContracts;

class ApplicationProvider {

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
        $this->app=$app;

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
     * @param $key
     * @param $object
     * @param null $concrete
     * @return mixed
     */
    public function register($key,$object,$concrete=null){

        //register app bound
        return $this->app()->bound->register($key,$object,$concrete);
    }

    /**
     * @param $key
     * @param null $object
     * @return mixed
     */
    public function terminate($key,$object=null){

        //terminate app bound
        return $this->app()->bound->terminate($key,$object);
    }

    /**
     * SymfonyRequest constructor.
     * @return \Symfony\Component\HttpFoundation\Request|\Store\Services\RequestService
     */
    public function request()
    {
        //symfony request
        return $this->app()->request;
    }

    /**
     * @param null $param
     * @param null $default
     * @return null
     */
    public function get($param=null,$default=null)
    {
        //symfony request query object
        $get=$this->app()->get;

        return ($param===null) ? $get : (isset($get[$param]) ? $get[$param] : $default);
    }

    /**
     * @param null $param
     * @param null $default
     * @return null
     */
    public function post($param=null,$default=null)
    {
        //symfony request query object
        $post=$this->app()->post;

        return ($param===null) ? $post : (isset($post[$param]) ? $post[$param] : $default);
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
        foreach ($this->request()->headers->all() as $key=>$value) {
            $list[$key]=$value;
        }

        //return header list
        return ($param===null) ? $list : (isset($list[$param]) ? $list[$param][0] : $default);
    }

    /**
     * @method url
     * @return mixed
     */
    public function url(){

        if(isset($this->app()->url)){

            //we assign the url object to the global kernel url object
            //so that it can be read anywhere in our route file.
            $this->url=$this->app()->url;
        }
    }

    /**
     * @param $class
     * @param array $bind
     * @return mixed
     */
    public function makeBind($class,$bind=array()){

        return Utils::makeBind($class,$this->providerBinding($bind));
    }

    /**
     * @param array $bind
     * @return mixed
     */
    public function providerBinding($bind=array()){

        return $this->app->applicationProviderBinding($this->app,$bind);
    }

    /**
     * @method getStatus
     * @return mixed
     */
    public function getStatus(){

        return $this->app()->responseStatus;
    }

    /**
     * @method getSuccess
     * @return mixed
     */
    public function getSuccess(){

        return $this->app()->responseSuccess;
    }

    /**
     * @method httpMethod
     * @return mixed
     */
    public function httpMethod(){

        return strtolower($this->app()->httpMethod);
    }

    /**
     * @method routeParameters
     * @return mixed
     */
    public function routeParameters(){

        return $this->app()->routeParameters;
    }

    /**
     * @method singleton
     * @return mixed
     */
    public function singleton(){

        return $this->app->singleton();
    }

    /**
     * @method container
     * @return mixed
     */
    public function container(){

        return (object)$this->app()->serviceContainer;
    }

    /**
     * @param $config null
     * @method config
     * @return mixed
     */
    public function config($config=null){

        return $this->singleton()->appClass->configLoaders($config);

    }

    /**
     * @return \Resta\Response\ResponseOutManager
     */
    public function response(){

        $object=debug_backtrace()[2]['object'];
        return new ResponseOutManager($object);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function pick($data=null,$key=null){

        if($data===null) return $this->singleton()->pick;

        if($key===null){
            $this->singleton()->pick[]=$data;
        }
        else{
            $this->singleton()->pick[$key]=$data;
        }

    }

    /**
     * @param $data
     * @return mixed
     */
    public function stack($data=null,$key=null){

        if($data===null) return $this->singleton()->stack;

        if($key===null){
            $this->singleton()->stack[]=$data;
        }
        else{
            $this->singleton()->stack[$key]=$data;
        }

    }
}