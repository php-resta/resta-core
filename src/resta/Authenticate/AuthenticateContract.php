<?php

namespace Resta\Authenticate;

interface AuthenticateContract
{
    /**
     * @return mixed
     */
    public function check();

    /**
     * @param $guard
     * @return mixed
     */
    public function guard($guard);

    /**
     * @return mixed
     */
    public function id();

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
     * @param $store
     * @return mixed
     */
    public function token();

    /**
     * @return mixed
     */
    public function user();

    /**
     * @return mixed
     */
    public function userData();
}