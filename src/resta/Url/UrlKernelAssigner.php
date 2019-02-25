<?php

namespace Resta\Url;

use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathList;

class UrlKernelAssigner extends ApplicationProvider
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