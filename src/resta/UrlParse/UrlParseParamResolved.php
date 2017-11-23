<?php

namespace Resta\UrlParse;

/**
 * Class UrlParseParamResolved
 * @package Resta\UrlParse
 */
class UrlParseParamResolved extends UrlParseException {

    /**
     * @var $url
     */
    public $url;

    /**
     * @method urlParamResolve
     * @param $app object
     * @return mixed
     */
    public function urlParamResolve($app){

        //check url parse data for exception
        $this->exception($this->url=$app->urlList);

        //discovery extension param; it is extension parameter on url
        //we can manage query path with this and set specific pretty url
        $this->discoverParamUrl();

        return $this->url;
    }

    /**
     * @method discoverPrettyUrl
     * @return void
     */
    public function discoverParamUrl(){

        $class=false;

        if(false===$class){

            $this->url['param']=$this->url['method'];
            $this->url['method']='index';
        }
    }
}