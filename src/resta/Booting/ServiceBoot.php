<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Resta\UrlParse\UrlParseApplication;

class ServiceBoot extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //With url parsing,the application route for
        //the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        $this->app->bind('serviceBoot',function(){
            return StaticPathModel::appVersionRoot().'\ServiceBootController';
        });
    }

}