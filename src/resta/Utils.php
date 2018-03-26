<?php

namespace Resta;

use Symfony\Component\Yaml\Yaml;

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

        if($class!==null){

            $container = self::callBuild();
            return $container->make($class,$param);
        }

        return null;

    }


    /**
     * @param $class null
     * @param array $param
     * @return mixed
     */
    public static function callBind($class=null, $param=array()){

        $container = self::callBuild();
        return $container->call($class,$param);
    }

    /**
     * @param $class null
     * @return mixed
     */
    public static function get($class=null){

        $container = self::callBuild();
        return $container->get($class);
    }

    /**
     * @param $argument
     * @param bool $shift
     * @return array
     */
    public static function upperCase($argument,$shift=true){

        if($shift){
            array_shift($argument);
        }

        return array_map(function($argument){
            return ucfirst($argument);
        },$argument);
    }


    /**
     * @param $argument
     * @return array
     */
    public static function strtolower($argument){

        if(!is_array($argument)){
            return strtolower($argument);
        }
        return array_map(function($argument){
            return strtolower($argument);
        },$argument);
    }


    /**
     * @param null $app
     * @return string
     */
    public static function getAppVersion($app=null,$appInstance=null){

        if(defined('app')){

            $versionClass='App\\'.$app.'\version';

            if(file_exists(self::getPathFromNamespace($versionClass)) && self::isNamespaceExists($versionClass)){
                $instance=new $versionClass;
                return $instance->handle($appInstance);
            }
        }


        return 'V1';
    }


    /**
     * @param array $data
     * @return string
     */
    public static function generatorNamespace($data=array()){

        return implode("\\",$data);
    }

    public static function getPathFromNamespace($class){

        $default=root.'/'.str_replace("\\","/",$class).'.php';
        return str_replace("/App",'/src/app',$default);
    }

    /**
     * @param $namespace
     * @return bool
     */
    public static function isNamespaceExists($namespace){

        return (class_exists($namespace)) ? true : false;
    }

    /**
     * @param $class
     * @param $method
     * @return bool
     */
    public static function existMethod($class,$method){

        return method_exists($class,$method);
    }

    public static function isArrayEqual($first,$second){

        return ( count( $first ) == count( $second ) && !array_diff( $first, $second ) );
    }

    public static function getYaml($path){

        return Yaml::parse(file_get_contents($path));
    }

    public static function glob($path,$filename=false){

        $configList=[];
        foreach (glob($path.'/*.php') as $config) {
            $configArray=str_replace(".php","",explode("/",$config));
            $configList[end($configArray)]=$config;
        }

        if($filename===true){
            return array_keys($configList);
        }
        return $configList;
    }

    public static function chmod($path) {
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $item) {
            chmod($item->getPathname(), 0777);
            if ($item->isDir() && !$item->isDot()) {
                self::chmod($item->getPathname());
            }
        }
    }

    public static function getJustClassName($namespace,$seperator="\\"){

        $path = explode($seperator, $namespace);
        return array_pop($path);
    }


    //fopen process
    public static function changeClass($class,$param=array()){
        $executionPath=$class;
        $dt = fopen($executionPath, "r");
        $content = fread($dt, filesize($executionPath));
        fclose($dt);
        foreach ($param as $key=>$value){
            $content=str_replace($key,$value,$content);
        }
        $dt = fopen($executionPath, "w");
        fwrite($dt, $content);
        fclose($dt);
        return true;
    }

    /**
     * @param $data
     * @param $callback
     * @return mixed
     */
    public static function returnCallback($data,$callback){

        return call_user_func_array($callback,[$data]);
    }

    /**
     * @param $namespace
     * @return string
     */
    public static function getNamespace($namespace){

        $rootDelete=str_replace(root.'/src/app/','',$namespace);


        return 'App\\'.self::generatorNamespace(
          explode('/',$rootDelete)
        );

    }

    /**
     * @param $callback
     * @return mixed
     */
    public static function callbackProcess($callback){
        return (is_callable($callback)) ? call_user_func($callback) : $callback;
    }




}