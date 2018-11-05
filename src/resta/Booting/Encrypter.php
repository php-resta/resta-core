<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Encrypter\Encrypter as EncrypterProvider;

class Encrypter extends ApplicationProvider implements BootContracts {

    /**
     * @return mixed|void
     */
    public function boot(){

        // the resta will assign a random key to your application for you.
        // this application will singularize the advantages of using the resta for your application in particular.
        $this->app->bind('encrypter',function(){
            return EncrypterProvider::class;
        });
    }
}