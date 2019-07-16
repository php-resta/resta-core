<?php

namespace Resta\Authenticate;

interface AuthenticateContract
{
    /**
     * @return mixed
     */
    public function check();

    /**
     * @return mixed
     */
    public function currentDeviceToken();

    /**
     * @return mixed
     */
    public function allDeviceTokens();

    /**
     * @param $guard
     * @return mixed
     */
    public function guard($guard);

    /**
     * @param array $credentials
     * @param bool $objectReturn
     * @return mixed
     */
    public function login($credentials=array(),$objectReturn=false);

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @param $store
     * @return mixed
     */
    public function store($store);

    /**
     * @return mixed
     */
    public function user();
    
}