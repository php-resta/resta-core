<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Traits\InstanceRegister;

class Environment extends ApplicationProvider  {

    /**
     * @method environment
     * @param null $environment
     */
    public function environment($environment=null){

        $this->register('env',(count($environment)) ? $environment['env'] : 'production');
        $this->register('var',$environment);
    }

}