<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Environment extends ApplicationProvider  {

    /**
     * @method environment
     * @param null $environment
     */
    public function environment($environment=null){

        $this->singleton()->env=(count($environment)) ? $environment['env'] : 'production';
        $this->singleton()->var=$environment;
    }

}