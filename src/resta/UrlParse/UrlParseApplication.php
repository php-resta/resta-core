<?php

namespace Resta\UrlParse;

use Resta\ApplicationProvider;
use Resta\Utils;

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
     * @return array
     */
    public function handle(){

        //convert array for query
        $query=$this->convertArrayForQuery();

        //determines the application name for your project
        $this->urlList['project']=(isset($query[0])) ? $query[0] : null;

        //determines the namespace for your project
        $this->urlList['namespace']=(isset($query[1])) ? $query[1] : null;

        //determines the endpoint for your project
        $this->urlList['endpoint']=(isset($query[2])) ? $query[2] : null;

        //determines the endpoint method for your project
        $this->urlList['method']=(isset($query[3])) ? $query[3] : null;

        return $this->urlList;
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

        //we set the first letter of the array elements
        //to be big according to the project standards
        return array_map(function($query){
            return ucfirst($query);
        },$arrayForQuery);
    }
}