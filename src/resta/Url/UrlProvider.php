<?php

namespace Resta\Url;

use Resta\Support\Arr;
use Resta\Foundation\ApplicationProvider;
use Resta\Support\Utils;

class UrlProvider extends ApplicationProvider
{
    /**
     * @var array $urlList
     */
    public $urlList = [];

    /**
     * @var $path
     */
    private $path;

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

        // if the version in the query array does not conform to the format,
        // it will be formatted.
        $query = $this->queryFormatProcess($query);

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
        //set path for query
        $query = $this->path;

        //convert array for query
        //we are removing the first empty element from the array due to the slash sign.
        if(is_null($this->path)){
            $query = $this->getRequestPathInfo();
        }

        array_shift($query);

        //we set the first letter of the array elements
        //to be big according to the project standards
        return array_map(
            function($query) {
                return ucfirst($query);
            },$query);
    }

    /**
     * url definitor
     *
     * @param $urlList
     * @return void
     */
    public function definitor($urlList)
    {
        define('version',$urlList['version']);
        define('endpoint',$urlList['endpoint']);
        define('app',$urlList['project']);
        define('method',$urlList['method']);

        //route parameters kernel object register
        $this->app->register('routeParameters',$urlList['parameters']);
    }

    /**
     * get path
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * get request path info
     *
     * @param array $path
     * @return array
     */
    public function getRequestPathInfo($path=array())
    {
        if(count($path)){
            $this->path = $path;
        }
        else{
            return Utils::getRequestPathInfo();
        }
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

    /**
     * get query format process
     *
     * @param $query
     * @return mixed
     */
    private function queryFormatProcess($query)
    {
        // at urlNames property,
        // we get the key of the version value registered.
        $versionKey = array_search('version',$this->urlNames);

        // if the query array has a version key,
        // and the value does not start with Vnumber, the version will definitely be formatted.
        if(isset($query[$versionKey]) && !preg_match('@V(\d+)@',$query[$versionKey])){
            $query = Arr::overwriteWith($query,[$versionKey=>'V1']);
        }

        return $query;
    }


}