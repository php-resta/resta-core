<?php

namespace Resta\Traits;

use Resta\Contracts\StaticPathContracts;
use Resta\StaticPathModel;
use Resta\StaticPathRepository;

trait ApplicationPath {

    /**
     * @return \StaticPathContracts
     */
    public function path(){
        return new StaticPathRepository();
    }


}