<?php

namespace Resta\Foundation\Bootstrapper;

use Resta\Foundation\ApplicationProvider;

class BootFireCallback extends ApplicationProvider
{
    /**
     * @param $booter
     */
    public static function loadBootstrappers($booter)
    {
        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        app()->resolve(FinalBooting::class,['boot'=>app()->manifest((end($booter)))]);
    }

    /**
     * @param array $kernel
     * @param callable $callback
     * @return mixed
     */
    public static function setBootFire($kernel=array(),callable $callback)
    {
        //The boot method to be executed can be specified by the user.
        //We use this method to know how to customize it.
        return call_user_func_array($callback,[self::setParametersForKernelCallback($kernel)]);
    }

    /**
     * @param $kernel
     * @return array
     */
    public static function setParametersForKernelCallback($kernel)
    {
        $kernel[] = self::class;
        return $kernel;
    }
}