<?php

namespace Resta\Authenticate\Driver;

interface BuilderContract {

    /**
     * @param $credentials
     * @return mixed
     */
    public function login($credentials);

    /**
     * @param $token
     * @return mixed
     */
    public function check($token);
}