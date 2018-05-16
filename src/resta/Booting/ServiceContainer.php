<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Resta\UrlParse\UrlParseApplication;

class ServiceContainer extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //With url parsing,the application route for
        //the rest project is determined after the route variables from the URL are assigned to the kernel url object.
        $this->app->bind('container',function(){
            return StaticPathModel::appVersionRoot().'\ServiceContainerController';
        },true);


    }

}