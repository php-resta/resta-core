<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Middleware\ApplicationMiddleware;

class Middleware extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //When your application is requested, the middleware classes are running before all bootstrapper executables.
        //Thus, if you make http request your application, you can verify with an intermediate middleware layer
        //and throw an exception.
        $this->app->bind('middleware',function(){
            return ApplicationMiddleware::class;
        });
    }

}