<?php

namespace Resta\GlobalLoaders;

use Resta\ApplicationProvider;

class Encrypter extends ApplicationProvider  {

    /**
     * register application key to kernel
     *
     * @param $key
     */
    public function applicationKey($key){

        //we are assigning a singleton object
        //so that we can use our application key in the project.
       $this->register('applicationKey',current($key));
    }

}