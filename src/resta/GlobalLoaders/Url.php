<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Url extends ApplicationProvider
{
    /**
     * @param $urlList
     * @return void
     */
    public function definitor($urlList)
    {
        //We define global URL objects globally for the application.
        define('group',             $urlList['namespace'].'');
        define('app',               $urlList['project'].'\\'.group);
        define('endpoint',          $urlList['endpoint']);
        define('method',            $urlList['method']);

        //route parameters kernel object register
        $this->register('routeParameters',$urlList['parameters']);
    }
}