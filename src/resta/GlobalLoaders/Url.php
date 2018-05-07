<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\StaticPathModel;
use Resta\Traits\InstanceRegister;

class Url extends ApplicationProvider  {

    /**
     * @param $urlList array
     * @method definitor
     */
    public function definitor($urlList){

        //We define global URL objects globally for the application.
        define('app',               $urlList['project']);
        define('namespace',         $urlList['namespace']);
        define('endpoint',          $urlList['endpoint']);
        define('method',            $urlList['method']);

        //global path definitors
        $this->globalPathDefinitor();

        //route parameters kernel object register
        $this->register('routeParameters',$urlList['parameters']);
    }

    private function globalPathDefinitor(){

        //global path definitors
        define('middleware',                StaticPathModel::appMiddlewarePath());
        define('appMiddlewarePath',         StaticPathModel::appMiddleware());
    }

}