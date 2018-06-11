<?php

namespace Resta\Container;

class RouteContainer {

    /**
     * @param $parameters
     * @param $param
     */
    public function handle($parameters,$param){

        // We record the route parameter with
        // the controller method in the serviceConf variable in the kernel..
        $method=strtolower(app()->singleton()->url['method']);
        app()->singleton()->bound->register('serviceConf','routeParameters',[$method=>$parameters]);

        $param['route']=route();

        $routeList=[];
        foreach ($parameters as $routeKey=>$routeVal){
            if(!isset($param['route'][$routeVal])){
                $routeList[$routeVal]=null;
            }
            else{
                $routeList[$routeVal]=strtolower($param['route'][$routeVal]);
            }
        }

        $param['route']=$routeList;

        return $param;
    }

}