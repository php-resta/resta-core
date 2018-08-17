<?php

namespace Resta\GlobalLoaders;

class Environment  {

    /**
     * register environment variables to kernel
     * @param null $configuration
     */
    public function set($configuration=null){

        //we are get the environment value
        $environment=(count($configuration)) ? $configuration['env'] : 'production';

        //we are doing global registration for env and var value.
        appInstance()->register('env',$environment);
        appInstance()->register('var',$configuration);
    }

}