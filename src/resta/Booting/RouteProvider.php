<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Routing\RouteApplication as Router;

class RouteProvider extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //route operations are the last part of the system run. In this section,
        //a route operation is passed through the url process and output is sent to the screen according to
        //the method file to be called by the application
        $this->app->bind()->router=(new Router())->handle();
    }

}