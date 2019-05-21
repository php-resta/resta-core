<?php

namespace Resta\Foundation\PathManager;

use Resta\Support\Utils;

class StaticNamespaceRepository extends StaticPathRepository
{
    /**
     * @return string
     */
    public function autoloadNamespace()
    {
        return StaticPathList::$autoloadNamespace;
    }

    /**
     * @return string
     */
    public function bootDir()
    {
        return StaticPathList::$boot;
    }

    /**
     * @return string
     */
    public function builder()
    {
        return Utils::getNamespace(parent::builder());
    }

    /**
     * @param null $endpoint
     * @param $bind
     * @return mixed|string
     */
    public function controller($endpoint=null,$bind=array())
    {
        $call=Utils::getNamespace($this->appCall());

        if($endpoint===null) return $call;

        $bundleName = $call.'\\'.ucfirst($endpoint).''.StaticPathList::$controllerBundleName.'';

        $endpointCall=$bundleName.'\\'.ucfirst($endpoint).''.StaticPathModel::$callClassPrefix;

        if($bind===true) return $endpointCall;

        if($bind=="bundle") return $bundleName;

        return app()->resolve($endpointCall,$bind);
    }

    /**
     * @return string
     */
    public function command()
    {
        return Utils::getNamespace(parent::command());
    }

    /**
     * @return mixed
     */
    public function config()
    {
        return Utils::getNamespace(parent::config());
    }

    /**
     * @return mixed
     */
    public function helpers()
    {
        return Utils::getNamespace(parent::helpers());
    }

    /**
     * @return mixed
     */
    public function tests()
    {
        return Utils::getNamespace(parent::tests());
    }

    /**
     * @return mixed
     */
    public function kernel()
    {
        return Utils::getNamespace(parent::kernel());
    }

    /**
     * @return mixed
     */
    public function provider()
    {
        return Utils::getNamespace(parent::provider());
    }

    /**
     * @return mixed
     */
    public function logger()
    {
        return Utils::getNamespace(parent::logger());
    }

    /**
     * @return mixed
     */
    public function middleware()
    {
        return Utils::getNamespace(parent::middleware());
    }

    /**
     * @return mixed
     */
    public function factory()
    {
        return Utils::getNamespace(parent::factory());
    }

    /**
     * @return mixed
     */
    public function migration()
    {
        return Utils::getNamespace(parent::migration());
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return Utils::getNamespace(parent::model());
    }

    /**
     * @return mixed
     */
    public function exception()
    {
        return Utils::getNamespace(parent::exception());
    }

    /**
     * @return mixed
     */
    public function optionalEvents()
    {
        return Utils::getNamespace(parent::optionalEvents());
    }

    /**
     * @return mixed
     */
    public function optionalJob()
    {
        return Utils::getNamespace(parent::optionalJob());
    }

    /**
     * @return mixed
     */
    public function optionalListeners()
    {
        return Utils::getNamespace(parent::optionalListeners());
    }

    /**
     * @return mixed
     */
    public function optionalSubscribers()
    {
        return Utils::getNamespace(parent::optionalSubscribers());
    }

    /**
     * @return mixed
     */
    public function optionalSource()
    {
        return Utils::getNamespace(parent::optionalSource());
    }

    /**
     * @return mixed
     */
    public function optionalWebservice()
    {
        return Utils::getNamespace(parent::optionalWebservice());
    }

    /**
     * @return mixed
     */
    public function request()
    {
        return Utils::getNamespace(parent::request());
    }

    /**
     * @return mixed
     */
    public function repository()
    {
        return Utils::getNamespace(parent::repository());
    }

    /**
     * @return mixed
     */
    public function route()
    {
        return Utils::getNamespace(parent::route());
    }

    /**
     * @return mixed
     */
    public function serviceAnnotations()
    {
        return Utils::getNamespace(parent::serviceAnnotations());
    }


    /**
     * @return mixed
     */
    public function serviceEventDispatcher()
    {
        return Utils::getNamespace(parent::ServiceEventDispatcher());
    }

    /**
     * @return mixed
     */
    public function serviceMiddleware()
    {
        return Utils::getNamespace(parent::serviceMiddleware());
    }

    /**
     * @return string
     */
    public function storeConfigDir()
    {
        return $this->storeDir().'\Config';
    }

    /**
     * @return string
     */
    public function storeDir()
    {
        return StaticPathList::$store;
    }

    /**
     * @return mixed
     */
    public function stubs()
    {
        return Utils::getNamespace(parent::stubs());
    }

    /**
     * @return mixed
     */
    public function version()
    {
        return Utils::getNamespace(parent::version());
    }
}