<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Response\ResponseApplication;

class ResponseManager extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // we determine kind of output with the response manager
        // json as default or [xml,wsdl]
        $this->app->bind('response',function(){
            return ResponseApplication::class;
        });
    }
}