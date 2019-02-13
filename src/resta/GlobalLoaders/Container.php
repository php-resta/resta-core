<?php

namespace Resta\GlobalLoaders;

use Resta\Foundation\ApplicationProvider;

class Container extends ApplicationProvider
{
    /**
     * @param $parameters
     * @return void
     */
    public function routeContainer($parameters)
    {
        // we record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method=strtolower(app()->singleton()->url['method']);

        // based on the serviceConf variable,
        // we are doing parameter bindings in the method context in the routeParameters array key.
        $this->register('serviceConf','routeParameters',[$method=>$parameters]);

    }
}