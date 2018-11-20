<?php

namespace Resta\UrlParse;

class UrlParseParamResolved extends UrlParseException
{
    /**
     * @var $url
     */
    public $url;

    /**
     * @param $app
     * @return mixed
     */
    public function urlParamResolve($app)
    {
        //check url parse data for exception
        $this->exception($this->url=$app->urlList);
        return $this->url;
    }
}