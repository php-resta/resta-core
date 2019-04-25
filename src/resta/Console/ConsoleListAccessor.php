<?php

namespace Resta\Console;

use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\Url\UrlVersionIdentifier;
use Resta\Foundation\PathManager\StaticPathList;
use Resta\Foundation\PathManager\StaticPathModel;

trait ConsoleListAccessor {

    public function projectPath()
    {
        if($this->project===null){
            throw new \InvalidArgumentException('Project name is invalid');
        }
        return StaticPathModel::projectPath(Str::slashToBackSlash(StaticPathList::$projectPrefixGroup),$this->project);
    }

    public function projectName(){

        $projectParse=explode("/",$this->project);
        return end($projectParse);
    }
    /**
     * @return mixed
     */
    public function kernel()
    {
        return $this->projectPath().''.StaticPathModel::$kernel;
    }

    /**
     * @return mixed
     */
    public function storage(){

        return $this->projectPath().''.StaticPathModel::$storage;
    }

    /**
     * @return mixed
     */
    public function version(){

        return $this->project.'/'.UrlVersionIdentifier::version();
    }


    /**
     * @return mixed
     */
    public function controller(){

        $this->argument['controller']=StaticPathModel::$controller;
        return $this->version().'/'.$this->argument['controller'];
    }

    /**
     * @return mixed
     */
    public function platform(){

        $this->argument['platform']=StaticPathModel::$platform;
        return $this->version().'/'.$this->argument['platform'];
    }


    /**
     * @return mixed
     */
    public function model(){

        $this->argument['model']=StaticPathModel::$model;
        return $this->version().'/'.StaticPathModel::$model;
    }


    /**
     * @return mixed
     */
    public function builder(){

        return $this->model().'/'.StaticPathModel::$builder;
    }


    /**
     * @return mixed
     */
    public function migration(){

        return $this->version().'/'.StaticPathModel::$migration;
    }


    /**
     * @return mixed
     */
    public function config(){

        return $this->version().'/'.StaticPathModel::$config;
    }

    /**
     * @return mixed
     */
    public function test(){

        return $this->projectPath().'/'.StaticPathModel::$test;
    }


    /**
     * @return mixed
     */
    public function optional(){

        return $this->version().'/'.StaticPathModel::$optional;
    }

    /**
     * @return mixed
     */
    public function events(){

        return $this->optional().'/'.StaticPathModel::$events;
    }

    /**
     * @return mixed
     */
    public function listeners(){

        return $this->optional().'/'.StaticPathModel::$listeners;
    }


    /**
     * @return mixed
     */
    public function sourceDir(){

        return $this->optional().'/'.StaticPathModel::$sourcePath;
    }

    /**
     * @return mixed
     */
    public function sourceEndpointDir(){

        return $this->optional().'/'.StaticPathModel::$sourcePath.'/Endpoint';
    }


    /**
     * @return mixed
     */
    public function sourceRequestDir(){

        return $this->optional().'/'.StaticPathModel::$sourceRequest;
    }

    /**
     * @return mixed
     */
    public function sourceSupportDir(){

        return $this->optional().'/'.StaticPathModel::$sourcePath.'/Support';
    }


    /**
     * @return mixed
     */
    public function repository(){

        return path()->repository();
    }

    /**
     * @return mixed
     */
    public function listener(){

        return $this->optional().'/'.StaticPathModel::$listener;
    }


    /**
     * @return mixed
     */
    public function job(){

        return $this->optional().'/'.StaticPathModel::$job;
    }


    /**
     * @return mixed
     */
    public function webservice(){

        return $this->projectPath().'/'.StaticPathModel::$webservice;
    }


    /**
     * @return mixed
     */
    public function log(){

        return $this->storage().'/'.StaticPathModel::$log;
    }

    /**
     * @return mixed
     */
    public function language(){

        return $this->storage().'/'.StaticPathModel::$language;
    }


    /**
     * @return mixed
     */
    public function resource(){

        return $this->storage().'/'.StaticPathModel::$resource;
    }


    /**
     * @return mixed
     */
    public function session(){

        return $this->storage().'/'.StaticPathModel::$session;
    }


    /**
     * @return mixed
     */
    public function middleware(){

        return $this->version().'/'.StaticPathModel::$middleware;
    }

    /**
     * @return mixed
     */
    public function factory(){

        return $this->version().'/'.StaticPathModel::$factory;
    }


    /**
     * @return mixed
     */
    public function node(){

        return $this->kernel().'/'.StaticPathModel::$node;
    }


    /**
     * @return mixed
     */
    public function once(){

        return $this->kernel().'/'.StaticPathModel::$once;
    }

    /**
     * @return mixed
     */
    public function command(){

        return $this->optional().'/'.StaticPathModel::$command;
    }


    /**
     * @return mixed
     */
    public function stub(){

        return $this->kernel().'/'.StaticPathModel::$stub;
    }

    /**
     * @return mixed
     */
    public function provider(){

        return $this->kernel().'/'.StaticPathModel::$provider;
    }


    /**
     * @return mixed
     */
    public function autoService(){

        return root.'/'.StaticPathModel::$appDefine.'/'.strtolower(StaticPathModel::$store).'/'.StaticPathModel::$autoService;
    }



}