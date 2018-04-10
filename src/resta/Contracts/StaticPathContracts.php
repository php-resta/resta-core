<?php

namespace Resta\Contracts;

interface StaticPathContracts {

    /**
     * @return mixed
     */
    public function encrypterFile();

    /**
     * @return mixed
     */
    public function environmentFile();

    /**
     * @return mixed
     */
    public function appStorage();

    /**
     * @return mixed
     */
    public function appResourche();

    /**
     * @param null $app
     * @return mixed
     */
    public function app($app=null);

    /**
     * @param null $app
     * @return mixed
     */
    public function appVersion($app=null);
}