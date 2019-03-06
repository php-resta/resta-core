<?php

namespace Resta\Support;

class Dependencies
{
    /**
     * @var array $bootLoaders
     */
    protected static $bootLoaders = ['url','environment','logger','config'];

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

                //with the boot loader kernel,we get the boot loader method.
                core()->bootLoader->call(function() use($loader,$kernelGroupList) {
                    return $this->{$kernelGroupList[$loader]}();
                });
            }
        }
    }

    /**
     * get dependency boot loaders
     *
     * @return array
     */
    public static function getBootLoaders()
    {
        return self::$bootLoaders;
    }

    /**
     * load config general bootstrap as need
     *
     * @return void|mixed
     */
    public static function loadBootstrapperNeedsForException()
    {
        static::bootLoader(self::$bootLoaders);
    }
}