<?php

namespace Resta\Response;

use Resta\ApplicationProvider;

class ResponseApplication extends ApplicationProvider {

    public function handle(){

        return 'json';
    }
}