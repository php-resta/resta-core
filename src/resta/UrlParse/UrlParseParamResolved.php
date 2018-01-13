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

        return $this->url;
    }
}