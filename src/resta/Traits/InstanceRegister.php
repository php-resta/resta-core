<?php

namespace Resta\Traits;

trait InstanceRegister {

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     */
    public function register($key,$object,$concrete=null){

        if(defined('appInstance')){
            $this->setAppSingleton($key,$object,$concrete);
            $this->setAppInstance($key,$object,$concrete);
        }
        else{
           $this->setAppSingleton($key,$object,$concrete);
        }
    }

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     */
    private function setAppInstance($key,$object,$concrete=null){

        $appInstance=\application::getAppInstance();

        if($concrete===null) {
            $appInstance->app->kernel->{$key}=$object;
        }
        else{
            $appInstance->app->kernel->{$key}[$object]=$concrete;
        }
    }

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     */
    private function setAppSingleton($key,$object,$concrete=null){

        if($concrete===null) {

            if(!isset($this->singleton()->{$key})){
                $this->singleton()->{$key}=$object;
            }

        }
        else{
            $this->singleton()->{$key}[$object]=$concrete;
        }
    }

}