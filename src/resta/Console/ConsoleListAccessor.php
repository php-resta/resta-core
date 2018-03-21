<?php

namespace Resta\Console;

use Resta\StaticPathModel;
use Resta\Utils;

trait ConsoleListAccessor {

    public function projectName(){

        $projectParse=explode("/",$this->project);
        return end($projectParse);
    }
    /**
     * @return mixed
     */
    public function kernel(){

        if($this->project===null){
            throw new \InvalidArgumentException('Project name is invalid');
        }
        return $this->project.'/'.StaticPathModel::$kernel;
    }

    /**
     * @return mixed
     */
    public function storage(){

        return $this->project.'/'.StaticPathModel::$storage;
    }

    /**
     * @return mixed
     */
    public function version(){

        return $this->project.'/'.Utils::getAppVersion($this->argument['project']);
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
    public function optional(){

        return $this->version().'/'.StaticPathModel::$optional;
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

        return $this->optional().'/'.StaticPathModel::$sourcePath.'/Request';
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

        return $this->optional().'/'.StaticPathModel::$repository;
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

        return $this->optional().'/'.StaticPathModel::$webservice;
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

        return $this->kernel().'/'.StaticPathModel::$command;
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
    public function autoService(){

        return root.'/'.StaticPathModel::$appDefine.'/'.strtolower(StaticPathModel::$store).'/'.StaticPathModel::$autoService;
    }



}