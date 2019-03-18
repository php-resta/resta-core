<?php

namespace Resta\Environment;

use Resta\Foundation\ApplicationProvider;

class EnvironmentKernelAssigner extends ApplicationProvider
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
        $this->app->register('env',$environment);
        $this->app->register('environmentVariables',$configuration);
    }
}