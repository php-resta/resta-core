<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;

class Url extends ApplicationProvider  {

    /**
     * @param $urlList array
     * @method definitor
     */
    public function definitor($urlList){

        //
        define('app',               $urlList['project']);
        define('namespace',         $urlList['namespace']);
        define('endpoint',          $urlList['endpoint']);
        define('method',            $urlList['method']);

        //
        $this->globalPathDefinitor();

        //
        $this->app->singleton()->routeParameters=$urlList['parameters'];
    }

    private function globalPathDefinitor(){

        define('middleware',                StaticPathModel::appMiddlewarePath());
        define('appMiddlewarePath',         StaticPathModel::appMiddleware());
    }

}