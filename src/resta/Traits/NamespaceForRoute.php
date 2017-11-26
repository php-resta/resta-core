<?php

namespace Resta\Traits;

use Resta\StaticPathModel;
use Resta\Utils;

trait NamespaceForRoute {

    /**
     * @return mixed
     */
    public function project(){

        return $this->url['project'];
    }


    /**
     * @return mixed
     */
    public function namespace(){

        return $this->url['namespace'];
    }


    /**
     * @return mixed
     */
    public function endpoint(){

        return $this->url['endpoint'];
    }


    /**
     * @return mixed
     */
    public function method(){

        return $this->url['method'];
    }

    /**
     * @return mixed
     */
    public function param(){

        return $this->url['param'];
    }


    public function getPrefixMethod(){

        return $this->method().''.StaticPathModel::$methodPrefix;
    }


    /**
     * @return mixed
     */
    public function getControllerNamespace(){

        //generator namespace for array
        return Utils::generatorNamespace([

            //composer autoload namespace
            StaticPathModel::$autoloadNamespace,

            //project name
            $this->project(),

            //project version name
            Utils::getAppVersion($this->project()),

            //controller static path name
            StaticPathModel::$controller,

            //endpoint name
            $this->endpoint(),

            //call file
            'GetService'
        ]);
    }



}