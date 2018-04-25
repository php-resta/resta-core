<?php

namespace Resta\Foundation;

use Resta\ApplicationProvider;

class RegisterAppBound extends ApplicationProvider {

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @param bool $unregister
     * @return mixed|void
     */
    public function register($key,$object,$concrete=null,$unregister=false){

        if(defined('appInstance')){
            $this->setAppSingleton($key,$object,$concrete,$unregister);
            $this->setAppInstance($key,$object,$concrete,$unregister);
        }
        else{
            $this->setAppSingleton($key,$object,$concrete,$unregister);
        }
    }

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @param bool $unregister
     */
    private function setAppInstance($key,$object,$concrete=null,$unregister=false){

        $appInstance=\application::getAppInstance();

        if($concrete===null) {

            if($unregister){
                unset( $appInstance->app->kernel->{$key});
            }
            else{
                $appInstance->app->kernel->{$key}=$object;
            }

        }
        else{

            if($unregister){
                unset($appInstance->app->kernel->{$key});
            }
            else{
                $appInstance->app->kernel->{$key}[$object]=$concrete;
            }

        }
    }

    /**
     * @param $key
     * @param $object
     * @param null $concrete
     * @param bool $unregister
     */
    private function setAppSingleton($key,$object,$concrete=null,$unregister=false){

        if($concrete===null) {

            if(!isset($this->singleton()->{$key})){

                if($unregister){
                    unset($this->singleton()->{$key});
                }
                else{
                    $this->singleton()->{$key}=$object;
                }

            }

        }
        else{

            if($unregister){
                unset($this->singleton()->{$key});
            }
            else{
                $this->singleton()->{$key}[$object]=$concrete;
            }

        }
    }


}