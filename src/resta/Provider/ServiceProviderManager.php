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
}