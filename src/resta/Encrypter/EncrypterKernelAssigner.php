<?php

namespace Resta\Encrypter;

use Resta\Foundation\ApplicationProvider;

class EncrypterKernelAssigner extends ApplicationProvider
{
    /**
     * @param $key
     * @return void
     */
    public function setApplicationKey($key){

        //we are assigning a singleton object
        //so that we can use our application key in the project.
       $this->app->register('applicationKey',current($key));
    }
}