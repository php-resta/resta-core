<?php

namespace Resta\Foundation;

use Resta\Utils;
use Resta\ApplicationProvider;

class BootFireCallback extends ApplicationProvider {

    /**
     * @param array $kernel
     * @param callable $callback
     * @return mixed
     */
    public static function setBootFire($kernel=array(),callable $callback){

        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        return call_user_func_array($callback,[self::setParametersForKernelCallback($kernel)]);
    }

    /**
     * @param $booter
     */
    public static function loadBootstrappers($booter){

        //We allocate our application class from
        //the booter path.
        $app=pos($booter);

        // We get instance for customBooter class
        // we get our object-protected boot lists
        // directly in the application class with the help of public access method.
        $customBooter   = $app->makeBind(CustomBooter::class,['boot'=>self::getBooter($booter)]);
        $boot           = ($customBooter)->customBootstrappers($booter);

        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        foreach ($boot as $bootstrapper){

            //set makeBuild for kernel boots
            Utils::makeBind($bootstrapper,$app->applicationProviderBinding($app))
                ->boot();
        }
    }

    /**
     * @param $kernel
     * @return array7
     */
    public static function setParametersForKernelCallback($kernel){

        $kernel[]=self::class;
        return $kernel;
    }

    /**
     * @param $booter
     * @return string
     */
    public static function getBooter($booter){

        return end($booter);
    }
}