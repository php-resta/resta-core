<?php

namespace Resta\Container\NameContainers;

class RouteContainer {

    /**
     * @param $parameters
     * @param $param
     */
    public function resolveContainer($parameters,$param){

        // we record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method=strtolower(app()->singleton()->url['method']);
        appInstance()->register('serviceConf','routeParameters',[$method=>$parameters]);

        $param['route']=route();

        return $param;
    }

}