<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Exception\ErrorHandler;

class Exception extends ApplicationProvider {

    /**
     * @method boot
     * @return void
     */
    public function boot(){

        //Sets a user-defined error handler function
        //This function can be used for defining your own way of handling errors during runtime,
        //for example in applications in which you need to do cleanup of data/files when a critical error happens,
        //or when you need to trigger an error under certain conditions (using trigger_error()).
        $this->app->bind('exception',function(){
            return ErrorHandler::class;
        });
    }

}