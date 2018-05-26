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
        define('endpoint',          $urlList['endpoint']);
        define('method',            $urlList['method']);

        //route parameters kernel object register
        $this->register('routeParameters',$urlList['parameters']);
    }
}