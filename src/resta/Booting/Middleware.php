<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Middleware\ApplicationMiddleware;

class Middleware extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // when your application is requested, the middleware classes are running before all bootstrapper executables.
        // thus, if you make http request your application, you can verify with an intermediate middleware layer
        // and throw an exception.
        $this->app->bind('middleware',function(){
            return ApplicationMiddleware::class;
        });
    }
}