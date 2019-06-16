<?php

namespace Resta\Support;

class Utils
{
    /**
     * @var array $bool
     */
    private static $bool = [];

    /**
     * string upper case
     *
     * @param $argument
     * @param bool $shift
     * @return array
     */
    public static function upperCase($argument,$shift=true)
    {
        if($shift){
            array_shift($argument);
        }

        return array_map(function($argument){
            return ucfirst($argument);
        },$argument);
    }

    /**
     * encrypt array data
     *
     * @param $class
     * @return string
     */
    public static function encryptArrayData($class)
    {
        //the serialized class data
        return md5(serialize($class));
    }


    /**
     * @param $argument
     * @return array|string
     */
    public static function strtolower($argument)
    {
        if(!is_array($argument)){
            return strtolower($argument);
        }
        return array_map(function($argument){
            return strtolower($argument);
        },$argument);
    }

    /**
     * @param array $data
     * @return string
     */
    public static function generatorNamespace($data=array())
    {
        return str_replace('.php','',implode("\\",$data));
    }

    /**
     * @param $class
     * @param bool $extension
     * @return mixed
     */
    public static function getPathFromNamespace($class,$extension=true)
    {
        if($extension){
            $default=root.'/'.str_replace("\\","/",$class).'.php';
        }
        else{
            $default=root.'/'.str_replace("\\","/",$class).'';
        }

        return str_replace("/App",'/src/app',$default);
    }

    /**
     * @param $namespace
     * @return bool
     */
    public static function isNamespaceExists($namespace)
    {
        return (is_string($namespace) && class_exists($namespace)) ? true : false;
    }

    /**
     * @param $class
     * @param $method
     * @return bool
     */
    public static function existMethod($class,$method)
    {
        return method_exists($class,$method);
    }

    /**
     * @param $first
     * @param $second
     * @return bool
     */
    public static function isArrayEqual($first,$second)
    {
        return (count( $first ) == count( $second ) && !array_diff( $first, $second ));
    }

    /**
     * @param $path
     * @return YamlManager
     */
    public static function yaml($path)
    {
        return new YamlManager($path);
    }

    /**
     * @param $path
     * @param bool $filename
     * @return array
     */
    public static function glob($path,$filename=false)
    {
        $configList = [];

        foreach (glob($path.'/*.php') as $config) {

            $configArray=str_replace(".php","",explode("/",$config));
            $configList[end($configArray)]=$config;
        }

        if($filename===true){
            return array_keys($configList);
        }

        return $configList;
    }

    /**
     * @param $path
     */
    public static function makeWritable($path)
    {
        $dir = new \DirectoryIterator($path);

        foreach ($dir as $item) {

            chmod($item->getPathname(), 0777);

            if ($item->isDir() && !$item->isDot()) {
                self::makeWritable($item->getPathname());
            }
        }
    }

    /**
     * @param $namespace
     * @param string $seperator
     * @return mixed
     */
    public static function getJustClassName($namespace,$seperator="\\")
    {
        $path = explode($seperator, $namespace);
        return array_pop($path);
    }

    /**
     * @param $class
     * @param array $param
     * @return bool
     */
    public static function changeClass($class,$param=array())
    {
        $executionPath=$class;
        $dt = fopen($executionPath, "r");

        if($dt!==false){

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

        return false;
    }

    /**
     * @param $data
     * @param $callback
     * @return mixed
     */
    public static function returnCallback($data,$callback)
    {
        return call_user_func_array($callback,[$data]);
    }

    /**
     * @param $namespace
     * @return string
     */
    public static function getNamespace($namespace)
    {
        $rootDelete=str_replace(root.''.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'','',$namespace);

        return 'App\\'.self::generatorNamespace(
                explode(''.DIRECTORY_SEPARATOR.'',$rootDelete)
            );

    }

    /**
     * @param $callback
     * @return mixed
     */
    public static function callbackProcess($callback)
    {
        return (is_callable($callback)) ? call_user_func_array($callback,[app()]) : $callback;
    }

    /**
     * @param $array1
     * @param $array2
     * @return bool
     */
    public static function array_diff_key_recursive ($array1, $array2)
    {
        if(count($array1)!==count($array2)) self::$bool[]=false;

        foreach ($array1 as $array1_key=>$array1_value){

            if(!is_array($array1_value)){
                if(!array_key_exists($array1_key,$array2)) self::$bool[]=false;
            }
            else{
                if(!array_key_exists($array1_key,$array2)) self::$bool[]=false;

                if(!isset($array2[$array1_key]) OR !is_array($array2[$array1_key])) $array2[$array1_key]=[];

                if(isset($array1_value[0])) $array1_value=$array1_value[0];

                if(isset($array2[$array1_key][0])) $array2[$array1_key]=$array2[$array1_key][0];

                self::array_diff_key_recursive($array1_value,$array2[$array1_key]);
            }
        }

        if(in_array(false,self::$bool)){
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function slashToBackSlash($data)
    {
        return str_replace("/","\\",$data);
    }

    /**
     * @param int $debug
     * @param null $key
     * @return null
     */
    public static function trace($debug=0,$key=null)
    {
        $trace=debug_backtrace();

        if($key===null){
            return $trace[$debug] ?? null;
        }
        return $trace[$debug][$key] ?? null;

    }

    /**
     * @param $dir
     * @param $dirPermissions
     * @param $filePermissions
     */
    public  static function chmod_r($dir, $dirPermissions, $filePermissions)
    {
        $dp = opendir($dir);
        while($file = readdir($dp)) {
            if (($file == ".") || ($file == ".."))
                continue;

            $fullPath = $dir."/".$file;

            if(is_dir($fullPath)) {
                chmod($fullPath, $dirPermissions);
                self::chmod_r($fullPath, $dirPermissions, $filePermissions);
            } else {
                chmod($fullPath, $filePermissions);
            }

        }
        closedir($dp);
    }

    /**
     * @return array
     */
    public static function getServiceConf()
    {
        if(property_exists(core(),'serviceConf') && defined('methodName')){
            return core()->serviceConf;
        }
        return [];
    }


    /**
     * @param $dir
     * @param bool $recursive
     * @param string $basedir
     * @param bool $include_dirs
     * @return array
     */
    public static function getAllFilesInDirectory($dir, $recursive = true, $basedir = '', $include_dirs = false)
    {
        if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
        if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
        if ($basedir == '') {$basedir = realpath($dir).DIRECTORY_SEPARATOR;}

        $files = scandir($dir);
        foreach ($files as $key => $value){
            if ( ($value != '.') && ($value != '..') ) {
                $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
                if (is_dir($path)) {
                    // optionally include directories in file list
                    if ($include_dirs) {$subresults[] = str_replace($basedir, '', $path);}
                    // optionally get file list for all subdirectories
                    if ($recursive) {
                        $subdirresults = self::getAllFilesInDirectory($path, $recursive, $basedir, $include_dirs);
                        $results = array_merge($results, $subdirresults);
                    }
                } else {
                    // strip basedir and add to subarray to separate file list
                    $subresults[] = str_replace($basedir, '', $path);
                }
            }
        }
        // merge the subarray to give the list of files then subdirectory files
        if (count($subresults) > 0) {$results = array_merge($subresults, $results);}
        return $results;
    }

    /**
     * @param $files
     * @param null $reelPath
     * @return array
     */
    public static function getPathWithPhpExtension($files,$reelPath=null)
    {
        $pathWithPhpList = [];

        foreach ($files as $file){

            if(preg_match('@(.*).php@is',$file,$pathWithPhp)){

                if($reelPath===null){
                    $pathWithPhpList[] = $pathWithPhp[0];
                }
                else{
                    $pathWithPhpList[] = $reelPath.'/'.$pathWithPhp[0];
                }

            }
        }

        return $pathWithPhpList;
    }

    /**
     * @return array
     */
    public static function getHttpMethods()
    {
        return [
            'get',
            'head',
            'post',
            'put',
            'delete',
            'connect',
            'options',
            'trace'
        ];
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function resolverClass($class)
    {
        if(self::isNamespaceExists($class)){
            return app()->resolve($class);
        }

        return $class;
    }

    /**
     * @return array
     */
    public static function getRequestPathInfo()
    {
        if(is_null(BootStaticManager::getRequestPath())){
            return explode("/",request()->getPathInfo());
        }
        return BootStaticManager::getRequestPath();
    }

    /**
     * @param $trace
     * @param null $remove
     * @return array
     */
    public static function removeTrace($trace,$remove=null)
    {
        $list = [];

        foreach($trace as $key=>$item){

            if(isset($item['file']) && !preg_match('@'.$remove.'@',$item['file'])){
                $list[$key] = $item;
            }
        }

        return array_values($list);
    }
}