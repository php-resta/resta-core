<?php

namespace Resta\Support;

class BootLoaderNeeds
{
    /**
     * load config general bootstrap as need
     *
     * @return void|mixed
     */
    public static function loadNeeds()
    {
        static::loadUrl();
        static::loadEnvironment();
        static::loadLogger();
        static::loadConfig();
    }

    /**
     * load config kernel bootstrap as need
     *
     * @return mixed
     */
    public static function loadConfig()
    {
        if(isset(core()->bindings['config'])===false){
            core()->bootLoader->call(function(){
                return $this->configProvider();
            });
        }
    }

    /**
     * load environment kernel bootstrap as need
     *
     * @return mixed
     */
    public static function loadEnvironment()
    {
        if(isset(core()->bindings['environment'])===false){
            core()->bootLoader->call(function(){
                return $this->environment();
            });
        }
    }

    /**
     * load logger kernel bootstrap as need
     *
     * @return mixed
     */
    public static function loadLogger()
    {
        if(isset(core()->bindings['logger'])===false && Utils::isNamespaceExists(app()->namespace()->logger())){
            core()->bootLoader->call(function(){
                return $this->logger();
            });
        }
    }


    /**
     * load url kernel bootstrap as need
     *
     * @return mixed
     */
    public static function loadUrl()
    {
        if(isset(core()->bindings['url'])===false){
            core()->bootLoader->call(function(){
                return $this->urlProvider();
            });
        }
    }
}