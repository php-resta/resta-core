<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Exception\ErrorHandler;
use Resta\Contracts\BootContracts;

class Exception extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // sets a user-defined error handler function
        // this function can be used for defining your own way of handling errors during runtime,
        // for example in applications in which you need to do cleanup of data/files when a critical error happens,
        // or when you need to trigger an error under certain conditions (using trigger_error()).
        $this->app->bind('exception',function(){
            return ErrorHandler::class;
        });
    }
}