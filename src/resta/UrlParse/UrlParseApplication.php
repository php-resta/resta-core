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
     * @method handle
     * @return array
     */
    public function handle(){

        //symfony request getPathInfo
        $this->query=$this->app->kernel()->request->getPathInfo();

        //convert array for query
        $query=$this->convertArrayForQuery();

        //assign url list
        $this->assignUrlList($query);

        //we make url parse resolving with resolved
        return (new UrlParseParamResolved)->urlParamResolve($this);
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


    /**
     * @method assignUrlList
     * @param array $query
     */
    public function assignUrlList($query=array()){

        //determines the application name for your project
        $this->urlList['project']=(strlen($query[0])>0) ? $query[0] : null;
        define('app',$this->urlList['project']);

        //determines the namespace for your project
        $this->urlList['namespace']=(isset($query[1])) ? $query[1] : null;
        define('namespace',$this->urlList['namespace']);

        //determines the endpoint for your project
        $this->urlList['endpoint']=(isset($query[2])) ? $query[2] : null;
        define('endpoint',$this->urlList['endpoint']);

        //determines the endpoint method for your project
        $this->urlList['method']=(isset($query[3])) ? $query[3] : 'index';
        define('method',$this->urlList['method']);
    }
}