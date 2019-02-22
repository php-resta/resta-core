<?php

namespace Resta\Routing;

use Resta\Foundation\ApplicationProvider;

class CheckEndpointForAutoService extends ApplicationProvider
{
    /**
     * @param $instance \Resta\Traits\NamespaceForRoute
     * @param callable|null $func
     * @return string
     */
    public function getNamespaceForAutoService($instance,callable $func = null)
    {
        //get namespace for auto service
        $autoServiceNamespace=$instance->autoService();

        // we are importing the settings
        // for AutoService from the config settings.
        $configAutoServices=config('autoservices');

        //auto service is invoked if auto service is allowed for any class yada config.
        if(class_exists($autoServiceNamespace) && $configAutoServices[strtolower(endpoint)]){
            return $autoServiceNamespace;
        }

        //normal services are called.
        return call_user_func($func);
    }
}