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
        $controllerNamespace = $this->parameters['namespace']['controller'] ?? null;

        if(!is_null($controllerNamespace)){
            return $controllerNamespace.'\\'.StaticPathList::$controller;
        }
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
    public function workers()
    {
        return Utils::getNamespace(parent::workers());
    }

    /**
     * @return mixed
     */
    public function schedule()
    {
        return Utils::getNamespace(parent::schedule());
    }

    /**
     * @return mixed
     */
    public function kernel()
    {
        if(isset($this->parameters['namespace']['kernel'])){
            return $this->parameters['namespace']['kernel'].'\\'.StaticPathList::$kernel;
        }

        die('kernel namespace in parameters file is not valid');
    }

    /**
     * @return mixed
     */
    public function manifest()
    {
        if(isset($this->parameters['namespace']['manifest'])){
            return $this->parameters['namespace']['manifest'].'\Manifest';
        }

        die('manifest namespace in parameters file is not valid');
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
        if(isset($this->parameters['namespace']['middleware'])){
            return $this->parameters['namespace']['middleware'].'\\'.StaticPathList::$middleware;
        }

        die('middleware namespace in parameters file is not valid');
    }

    /**
     * @return mixed
     */
    public function factory()
    {
        if(isset($this->parameters['namespace']['factory'])){
            return $this->parameters['namespace']['factory'].'\\'.StaticPathList::$factory;
        }

        die('factory namespace in parameters file is not valid');
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
        if(isset($this->parameters['namespace']['exception'])){
            return $this->parameters['namespace']['exception'].'\\'.StaticPathList::$exception;
        }

        die('middleware namespace in parameters file is not valid');
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
        if(isset($this->parameters['namespace']['client'])){
            return $this->parameters['namespace']['client'].'\Client';
        }

        die('repository namespace in parameters file is not valid');
    }

    /**
     * @return mixed
     */
    public function repository()
    {
        if(isset($this->parameters['namespace']['repository'])){
            return $this->parameters['namespace']['repository'].'\\'.StaticPathList::$repository;
        }

        die('repository namespace in parameters file is not valid');
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
        if(isset($this->parameters['namespace']['serviceAnnotation'])){
            return $this->parameters['namespace']['serviceAnnotation'].'\\'.StaticPathList::$serviceAnnotations;
        }

        die('service annotation namespace in parameters file is not valid');
    }


    /**
     * @return mixed
     */
    public function serviceEventDispatcher()
    {
        if(isset($this->parameters['namespace']['eventDispatcherManager'])){
            return $this->parameters['namespace']['eventDispatcherManager'].'\ServiceEventDispatcherManager';
        }

        die('service event dispatcher namespace in parameters file is not valid'.PHP_EOL);
    }

    /**
     * @return mixed
     */
    public function serviceMiddleware()
    {
        if(isset($this->parameters['namespace']['serviceMiddleware'])){
            return $this->parameters['namespace']['serviceMiddleware'].'\\'.StaticPathList::$serviceMiddleware;
        }

        die('service middleware namespace in parameters file is not valid');
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