<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Environment extends ApplicationProvider  {

    /**
     * register environment variables to kernel
     *
     * @method environment
     * @param null $environment
     */
    public function environment($environment=null){

        $this->register('env',(count($environment)) ? $environment['env'] : 'production');
        $this->register('var',$environment);
    }

}