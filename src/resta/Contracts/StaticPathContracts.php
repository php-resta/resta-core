<?php

namespace Resta\Contracts;

interface StaticPathContracts {

    /**
     * @param null $app
     * @return mixed
     */
    public function app($app=null);

    /**
     * @return mixed
     */
    public function appLanguage();

    /**
     * @return mixed
     */
    public function appStorage();

    /**
     * @return mixed
     */
    public function config();

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
    public function kernel();

    /**
     * @return mixed
     */
    public function logger();

    /**
     * @return mixed
     */
    public function middleware();

    /**
     * @return mixed
     */
    public function migration();

    /**
     * @return mixed
     */
    public function model();

    /**
     * @return mixed
     */
    public function optionalWebservice();

    /**
     * @return mixed
     */
    public function optionalJob();

    /**
     * @return mixed
     */
    public function optionalException();

    /**
     * @return mixed
     */
    public function optionalSource();

    /**
     * @return mixed
     */
    public function appResourche();

    /**
     * @return mixed
     */
    public function serviceContainer();

    /**
     * @param null $app
     * @return mixed
     */
    public function version($app=null);
}