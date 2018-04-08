<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Middleware\ExcludeMiddleware;
use Resta\Traits\InstanceRegister;

class Middleware extends ApplicationProvider  {

    //Instance register
    use InstanceRegister;

    public function setAppInstance(){

        //We are logging the kernel for the middleware class and the exclude class.
        $this->singleton()->middlewareClass=$this->makeBind(appMiddlewarePath);
        $this->singleton()->excludeClass=$this->makeBind(ExcludeMiddleware::class);

    }

}