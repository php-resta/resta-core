<?php

namespace Resta\UrlParse;

use Resta\ApplicationProvider;

/**
 * Class UrlParseApplication
 * @package Resta\UrlParse
 */
class UrlParseApplication extends ApplicationProvider{

    /**
     * @var $query
     */
    public $query;

    /**
     * @var array
     */
    public $urlList=[];

    /**
     * UrlParseApplication constructor.
     * @param $app
     */
    public function __construct($app){

        //provider construct binding
        //get path info with request component
        parent::__construct($app);
        $this->query=$this->app->kernel()->request->getPathInfo();

    }

    /**
     * @method handle
     * @return object
     */
    public function handle(){

        //convert array for query
        $query=$this->convertArrayForQuery();

        //determines the application name for your project
        $this->urlList['project']=$query[0];

        //determines the namespace for your project
        $this->urlList['namespace']=$query[1];

        //determines the endpoint for your project
        $this->urlList['endpoint']=$query[2];

        //determines the endpoint method for your project
        $this->urlList['method']=$query[3];

        return (object)$this->urlList;
    }

    /**
     * @method convertArrayForQuery
     * @return array
     */
    public function convertArrayForQuery(){

        //convert array for query
        //we are removing the first empty element from the array due to the slash sign.
        $arrayForQuery=explode("/",$this->query);
        array_shift($arrayForQuery);
        return $arrayForQuery;
    }
}