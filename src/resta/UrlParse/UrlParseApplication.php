<?php

namespace Resta\UrlParse;

use Resta\Utils;
use Resta\StaticPathList;

class UrlParseApplication {

    /**
     * @var array
     */
    public $urlList=[];

    /**
     * @var array $urlNames
     */
    protected $urlNames=['project','namespace','endpoint','method'];

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

            //set url list for urlNames property
            if(isset($this->urlNames[$key])){
                $this->getUrlListValues($key,$value);
            }
        }

        // If there is no method key in the urlList property,
        // then by default we assign the index to the method property.
        if(!isset($this->urlList['method'])){
            $this->urlList['method']='index';
        }

        //determines the endpoint method for your project
        $this->urlList['parameters']=array_slice($query,3);

        //url global instance
        resta()->urlGlobalInstance->definitor($this->urlList);

    }

    /**
     * @method convertArrayForQuery
     * @return array
     */
    public function convertArrayForQuery(){

        //convert array for query
        //we are removing the first empty element from the array due to the slash sign.
        $arrayForQuery=explode("/",request()->getPathInfo());
        array_shift($arrayForQuery);

        //we set the first letter of the array elements
        //to be big according to the project standards
        return array_map(
            function($query) {
                return ucfirst($query);
            },$arrayForQuery);
    }

    /**
     * @param $key
     * @param $value
     */
    private function getUrlListValues($key,$value){

        if($this->urlNames[$key]=="namespace"){

            // If the key value of the url is specified as a namespace,
            // then in this case we are converting this group value to namespace format.
            $projectPrefixNamespace=Utils::slashToBackSlash(StaticPathList::$projectPrefix);
            $this->urlList[$this->urlNames[$key]]=(strlen($value)>0) ? $projectPrefixNamespace.'\\'.$value : null;
        }
        else{

            //If the value from the url is an external value, the default format is applied.
            $this->urlList[$this->urlNames[$key]]=(strlen($value)>0) ? $value : null;
        }
    }


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


}