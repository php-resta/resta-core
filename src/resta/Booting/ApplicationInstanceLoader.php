<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;

class ApplicationInstanceLoader extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //we define the general application instance object.
        define('appInstance',(base64_encode(serialize($this))));
    }
}