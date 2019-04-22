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
        //We allocate our application class from
        //the booter path.
        $app = pos($booter);

        // We get instance for customBooter class
        // we get our object-protected boot lists
        // directly in the application class with the help of public access method.
        $customBooter   = $app->resolve(CustomBooter::class,['boot'=>end($booter)]);
        $boot           = ($customBooter)->customBootstrappers($booter);

        // and as a result we now use
        //the instance properties of our boot lists to include our implementation.
        $app->resolve(FinalBooting::class,['boot'=>$boot]);
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