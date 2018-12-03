<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;
use Resta\Contracts\BootContracts;
use Resta\Encrypter\Encrypter as EncrypterProvider;

class Encrypter extends ApplicationProvider implements BootContracts
{
    /**
     * @return mixed|void
     */
    public function boot()
    {
        // the rest system will assign a random key to your application for you.
        // this application will single the advantages of using the rest system for your application in particular.
        $this->app->bind('encrypter',function(){
            return EncrypterProvider::class;
        });
    }
}