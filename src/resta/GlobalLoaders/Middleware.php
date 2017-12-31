<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Middleware extends ApplicationProvider  {

    public function setAppInstance(){

        define('appInstance',(base64_encode(serialize($this))));
    }

}