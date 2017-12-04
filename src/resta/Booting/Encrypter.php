<?php

namespace Resta\Booting;

use Resta\ApplicationProvider;

class Encrypter extends ApplicationProvider {

    public function boot(){

        $this->app->singleton()->encrypter='encrypter';
    }
}