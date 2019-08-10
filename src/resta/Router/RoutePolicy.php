<?php

namespace Resta\Router;

use Resta\Foundation\ApplicationProvider;

class RoutePolicy extends ApplicationProvider
{
    /**
     * get policy gate
     *
     * @param callable $callback
     * @return mixed|void
     */
    public function gate(callable $callback)
    {
        $di = $this->app['di'];

        if(!method_exists(policy(),Route::getRouteControllerMethod())){
            $directly = true;
        }

        if(isset($directly) || $di(policy(),Route::getRouteControllerMethod())){
            return call_user_func($callback);
        }

        exception()->accessDeniedHttp('Your access has been denied.You have limited authority for this section.');
    }
}