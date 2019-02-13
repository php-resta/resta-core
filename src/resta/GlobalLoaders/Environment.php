<?php

namespace Resta\GlobalLoaders;

use Resta\Foundation\ApplicationProvider;

class Environment extends ApplicationProvider
{
    /**
     * @param null $configuration
     * @return void
     */
    public function set($configuration=null)
    {
        //we are get the environment value
        $environment=(count($configuration)) ? $configuration['env'] : 'production';

        //we are doing global registration for env and var value.
        $this->register('env',$environment);
        $this->register('var',$configuration);
    }
}