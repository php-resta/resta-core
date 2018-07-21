<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Environment extends ApplicationProvider  {

    /**
     * register environment variables to kernel
     * @param null $configuration
     */
    public function set($configuration=null){

        $this->register('env',(count($configuration)) ? $configuration['env'] : 'production');
        $this->register('var',$configuration);
    }

}