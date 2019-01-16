<?php

namespace Resta\Contracts;

interface StaticNamespaceContracts
{
    /**
     * @return mixed
     */
    public function bootDir();

    /**
     * @return mixed
     */
    public function builder();

    /**
     * @param null $endpoint
     * @param array $bind
     * @return mixed
     */
    public function controller($endpoint=null,$bind=array());

    /**
     * @param null $command
     * @param array $argument
     * @return mixed
     */
    public function command($command=null,$argument=array());

    /**
     * @return mixed
     */
    public function config();

    /**
     * @return mixed
     */
    public function factory();

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
    public function optionalException();

    /**
     * @return mixed
     */
    public function optionalEvents();

    /**
     * @return mixed
     */
    public function optionalJob();

    /**
     * @return mixed
     */
    public function optionalListeners();

    /**
     * @return mixed
     */
    public function optionalSubscribers();


    /**
     * @return mixed
     */
    public function repository();

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
    public function optionalSource();

    /**
     * @return mixed
     */
    public function optionalWebservice();

    /**
     * @return mixed
     */
    public function serviceAnnotations();

    /**
     * @return mixed
     */
    public function serviceContainer();

    /**
     * @return mixed
     */
    public function serviceEventDispatcher();

    /**
     * @return mixed
     */
    public function serviceMiddleware();

    /**
     * @return mixed
     */
    public function stubs();

    /**
     * @return mixed
     */
    public function version();
}