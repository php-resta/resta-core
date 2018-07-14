<?php

namespace Resta\Authenticate\Driver;

interface BuilderContract {

    /**
     * @param $app
     * @param $credentials
     * @return mixed
     */
    public function login($app,$credentials);
}