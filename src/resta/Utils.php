<?php

namespace Resta;

class Utils {


    /**
     * @return mixed
     */
    public static function callBuild(){

        //di-container
        return \DI\ContainerBuilder::buildDevContainer();
    }


    /**
     * @param null $class
     * @return mixed
     */
    public static function resolve($class=null){

        //class resolve
        if($class!==null){
            $container = self::callBuild();
            return $container->get($class);
        }

    }


    /**
     * @param $class null
     * @param array $param
     * @return mixed
     */
    public static function makeBind($class=null, $param=array()){

        $container = self::callBuild();
        return $container->make($class,$param);
    }

    public static function upperCase($argument){

        array_shift($argument);
        return array_map(function($argument){
            return ucfirst($argument);
        },$argument);
    }
}