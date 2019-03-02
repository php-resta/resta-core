<?php

namespace Resta\Provider;

use Resta\Foundation\ApplicationProvider;

class ServiceProviderManager extends  ApplicationProvider
{
    /**
     * get base class name
     *
     * @return string
     */
    public function baseClassName()
    {
        return get_called_class();
    }

    /**
     * get dependencies property for provider
     *
     * @return mixed
     */
    public function dependencies()
    {
        //check if the property is exist
        if(property_exists($this,'dependencies')){
            return (array)$this->dependencies;
        }

        return [];
    }
}