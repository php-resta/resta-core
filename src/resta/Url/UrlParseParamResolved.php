<?php

namespace Resta\Url;

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
        $this->url = $app->urlList;

        if(is_null($app->getPath())){
            $this->exception($this->url);
        }

        return $this->url;
    }
}