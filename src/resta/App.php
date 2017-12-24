<?php

namespace Resta;

use Store\Services\HttpSession as Session;
use Store\Services\Redis as Redis;

/**
 * Class App
 * @package Resta
 */
class App {

    /**
     * @return mixed
     */
    public static function getAppInstance(){

        //we save an instance for the entire application
        //and add it to the helper file to be accessed from anywhere in the application.
        return unserialize(base64_decode(appInstance));
    }

    /**
     * @param $service
     * @param $arg
     */
    public static function annotationsLoaders($service,$arg){

        return self::$service();
    }

    /**
     * @return Session
     */
    private static function session(){

        return new Session();
    }

    /**
     * @return \Predis\Client
     */
    private static function redis(){

        return new Redis();
    }
}