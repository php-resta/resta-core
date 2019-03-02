<?php

namespace Resta\Support;

class Dependencies
{
    /**
     * load bootstrapper dependencies
     *
     * @param array $loaders
     */
    public static function bootLoader($loaders=array())
    {
        //get kernel group list from application
        $kernelGroupList = app()->kernelGroupList();

        foreach ($loaders as $loader){
            if(isset($kernelGroupList[$loader]) && isset(core()->bindings[$loader])===false){

                //with the bootloader kernel,we get the bootloader method.
                core()->bootLoader->call(function() use($loader,$kernelGroupList) {
                    return $this->{$kernelGroupList[$loader]}();
                });
            }
        }
    }

    /**
     * load config general bootstrap as need
     *
     * @return void|mixed
     */
    public static function loadBootstrapperNeedsForException()
    {
        static::bootLoader(['url','environment','logger','config']);
    }
}