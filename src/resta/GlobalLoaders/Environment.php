<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;
use Resta\Traits\InstanceRegister;

class Environment extends ApplicationProvider  {

    //Instance register
    use InstanceRegister;

    /**
     * @method environment
     * @param null $environment
     */
    public function environment($environment=null){

        $this->register('env',(count($environment)) ? $environment['env'] : 'production');
        $this->register('var',$environment);
    }

}