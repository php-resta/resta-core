<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Middleware\ExcludeMiddleware;

class Middleware extends ApplicationProvider  {

    public function setAppInstance(){

        //
        $this->singleton()->middlewareClass=$this->makeBind(appMiddlewarePath);
        $this->singleton()->excludeClass=$this->makeBind(ExcludeMiddleware::class);

        define('appInstance',(base64_encode(serialize($this))));
    }

}