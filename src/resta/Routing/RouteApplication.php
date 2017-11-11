<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;

class RouteApplication extends ApplicationProvider {

    public function handle(){

        dd($this->app->kernel()->url);
    }
}