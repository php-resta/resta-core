<?php

namespace Resta\Contracts;

interface StaticPathContracts
{
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
    public function appResourche();

    /**
     * @return mixed
     */
    public function appStorage();

    /**
     * @return mixed
     */
    public function autoloadNamespace();

    /**
     * @return mixed
     */
    public function bootDir();

    /**
     * @return mixed
     */
    public function command();

    /**
     * @return mixed
     */
    public function config();

    /**
     * @return mixed
     */
    public function helpers();

    /**
     * @return mixed
     */
    public function tests();

    /**
     * @param null $controller
     * @param bool $bool
     * @return mixed
     */
    public function controller($controller=null,$bool=true);

    /**
     * @return mixed
     */
    public function factory();

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
    public function exception();

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
    public function provider();

    /**
     * @return mixed
     */
    public function optionalWebservice();

    /**
     * @return mixed
     */
    public function request();

    /**
     * @return mixed
     */
    public function route();

    /**
     * @return mixed
     */
    public function serviceMiddleware();

    /**
     * @return mixed
     */
    public function storeConfigDir();

    /**
     * @return mixed
     */
    public function storeDir();

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