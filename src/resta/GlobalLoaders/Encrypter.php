<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Encrypter extends ApplicationProvider
{
    /**
     * @param $key
     * @return void
     */
    public function setApplicationKey($key){

        //we are assigning a singleton object
        //so that we can use our application key in the project.
       $this->register('applicationKey',current($key));
    }

}