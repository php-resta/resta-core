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
    public function appLog();

    /**
     * @return mixed
     */
    public function appStorage();

    /**
     * @return mixed
     */
    public function config();

    /**
     * @param null $controller
     * @param bool $bool
     * @return mixed
     */
    public function controller($controller=null,$bool=true);

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
     * @return mixed
     */
    public function stubs();

    /**
     * @param null $app
     * @return mixed
     */
    public function version($app=null);
}