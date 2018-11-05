<?php

namespace Resta\Authenticate\Driver;

interface BuilderContract {

    /**
     * @param $token
     * @return mixed
     */
    public function check($token);

    /**
     * @param $credentials
     * @return mixed
     */
    public function login($credentials);

    /**
     * @param $credentials
     * @return mixed
     */
    public function logout($credentials);


}