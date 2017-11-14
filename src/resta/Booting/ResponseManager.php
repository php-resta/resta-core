<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Response\ResponseApplication;

class ResponseManager extends ApplicationProvider {

    public function boot(){

        //We determine kind of output with the response manager
        //json as default or [xml,wsdl]
        $this->app->bind('response',function(){
            return ResponseApplication::class;
        });
    }
}