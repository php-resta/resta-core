<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Encrypter\Encrypter as EncrypterProvider;

class Encrypter extends ApplicationProvider {

    public function boot(){

        $this->app->bind('encrypter',function(){
            return EncrypterProvider::class;
        });
    }
}