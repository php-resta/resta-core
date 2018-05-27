<?php

namespace Resta\Routing;

use Resta\ApplicationProvider;

class CheckEndpointForAutoService extends ApplicationProvider{

    /**
     * @param $instance \Resta\Traits\NamespaceForRoute
     * @param callable|null $func
     * @return string
     */
    public function getNamespaceForAutoService($instance,callable $func = null){

        $autoServiceNamespace=$instance->autoService();

        if(class_exists($autoServiceNamespace)){
            return $autoServiceNamespace;
        }
        return call_user_func($func);
    }
}