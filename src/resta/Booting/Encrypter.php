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

        $this->app->bind('encrypter',function(){
            return EncrypterProvider::class;
        },true);
    }
}