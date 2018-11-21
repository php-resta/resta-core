<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Routing\RouteApplication as Router;

class RouteProvider extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // route operations are the last part of the system run. In this section,
        // a route operation is passed through the url process and output is sent to the screen according to
        // the method file to be called by the application
        $this->app->bind('router',function(){
            return Router::class;
        });
    }
}