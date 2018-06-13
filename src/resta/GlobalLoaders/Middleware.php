<?php

namespace Resta\GlobalLoaders;

use Resta\StaticPathModel;
use Resta\ApplicationProvider;
use Resta\Middleware\ExcludeMiddleware;

class Middleware extends ApplicationProvider  {

    public function setAppInstance(){

        //We are logging the kernel for the middleware class and the exclude class.
        $this->singleton()->middlewareClass     = $this->makeBind(StaticPathModel::appMiddleware());
        $this->singleton()->excludeClass        = $this->makeBind(ExcludeMiddleware::class);

    }

}