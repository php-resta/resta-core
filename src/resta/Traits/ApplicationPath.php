<?php

namespace Resta\Traits;

use Resta\StaticPathRepository;
use Resta\StaticNamespaceRepository;
use Resta\Contracts\StaticPathContracts;
use Resta\Contracts\StaticNamespaceContracts;

trait ApplicationPath {

    /**
     * @return \StaticPathContracts
     */
    public function path(){
        return new StaticPathRepository();
    }

    /**
     * @return \StaticNamespaceContracts
     */
    public function namespace(){
        return new StaticNamespaceRepository();
    }


}