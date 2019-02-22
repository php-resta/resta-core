<?php

namespace Resta\GlobalLoaders;

use Resta\Foundation\StaticPathList;
use Resta\Foundation\ApplicationProvider;

class Url extends ApplicationProvider
{
    /**
     * @param $urlList
     * @return void
     */
    public function definitor($urlList)
    {
        //We define global URL objects globally for the application.
        define('version',           $urlList['version'].'');
        define('app',               $urlList['project']);
        define('endpoint',          $urlList['endpoint']);
        define('method',            $urlList['method']);

        //route parameters kernel object register
        $this->register('routeParameters',$urlList['parameters']);
    }
}