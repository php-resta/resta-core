<?php

namespace Resta\Url;

use Resta\Support\Utils;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathList;

class UrlProvider extends ApplicationProvider
{
    /**
     * @var array $urlList
     */
    public $urlList = [];

    /**
     * @var array $urlNames
     */
    protected $urlNames = ['project','version','endpoint','method'];

    /**
     * assign url list
     *
     * @return void
     */
    public function assignUrlList()
    {
        // We treat the url parameters in the size of
        // the application usage and get the values
        // ​​to be processed throughout the application in query format.
        $query = $this->convertArrayForQuery();

        foreach ($query as $key=>$value){

            //set url list for urlNames property
            if(isset($this->urlNames[$key])){
                $this->getUrlListValues($key,$value);
            }
        }

        // If there is no method key in the urlList property,
        // then by default we assign the index to the method property.
        if(!isset($this->urlList['method'])){
            $this->urlList['method'] = 'index';
        }

        //determines the endpoint method for your project
        $this->urlList['parameters'] = array_slice($query,3);

        //url global instance
        $this->definitor($this->urlList);

    }

    /**
     * convert array for query
     *
     * @return array
     */
    public function convertArrayForQuery()
    {
        //convert array for query
        //we are removing the first empty element from the array due to the slash sign.
        $arrayForQuery = explode("/",request()->getPathInfo());

        array_shift($arrayForQuery);

        //we set the first letter of the array elements
        //to be big according to the project standards
        return array_map(
            function($query) {
                return ucfirst($query);
            },$arrayForQuery);
    }

    /**
     * url definitor
     *
     * @param $urlList
     * @return void
     */
    public function definitor($urlList)
    {
        //We define global URL objects globally for the application.
        define('version',$urlList['version'].'');
        define('app',$urlList['project']);
        define('endpoint',$urlList['endpoint']);
        define('method',$urlList['method']);

        //route parameters kernel object register
        $this->app->register('routeParameters',$urlList['parameters']);
    }

    /**
     * get url list values
     *
     * @param $key
     * @param $value
     */
    private function getUrlListValues($key,$value)
    {
        //If the value from the url is an external value, the default format is applied.
        $this->urlList[$this->urlNames[$key]] = (strlen($value)>0) ? $value : null;
    }


    /**
     * url provider application handle
     *
     * @return mixed
     */
    public function handle()
    {
        //convert array for query
        //assign url list
        $this->assignUrlList();

        //register to container urlComponent value
        $this->app->register('urlComponent',$this->urlList);

        //we make url parse resolving with resolved
        return (new UrlParseParamResolved)->urlParamResolve($this);
    }


}