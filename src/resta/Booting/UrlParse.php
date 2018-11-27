<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\UrlParse\UrlParseApplication;

class UrlParse extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // with url parsing,the application route for
        // the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        $this->app->bind('url',function(){
            return UrlParseApplication::class;
        });
    }
}