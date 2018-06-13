<?php

namespace Resta\UrlParse;

use Resta\ApplicationProvider;

class UrlParseApplication extends ApplicationProvider{

    /**
     * @var array
     */
    public $urlList=[];

    /**
     * @var array $urlNames
     */
    protected $urlNames=['project','namespace','endpoint','method'];


    /**
     * @method handle
     * @return array
     */
    public function handle(){

        //convert array for query
        //assign url list
        $this->assignUrlList();

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
        $arrayForQuery=explode("/",$this->request()->getPathInfo());
        array_shift($arrayForQuery);

        //we set the first letter of the array elements
        //to be big according to the project standards
        return array_map(
            function($query) {
                return ucfirst($query);
                },$arrayForQuery);
    }


    /**
     * @method assignUrlList
     * @param array $query
     */
    public function assignUrlList(){

        // We treat the url parameters in the size of
        // the application usage and get the values
        // ​​to be processed throughout the application in query format.
        $query=$this->convertArrayForQuery();

        foreach ($query as $key=>$value){
            $this->urlList[$this->urlNames[$key]]=(strlen($value)>0) ? $value : null;
        }

        // If there is no method key in the urlList property,
        // then by default we assign the index to the method property.
        if(!isset($this->urlList['method'])){
            $this->urlList['method']='index';
        }

        //determines the endpoint method for your project
        $this->urlList['parameters']=array_slice($query,3);

        //url global instance
        $this->singleton()->urlGlobalInstance->definitor($this->urlList);


    }
}