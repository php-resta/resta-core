<?php

namespace Resta\Support;

use Resta\Exception\FileNotFoundException;
use Zend\Crypt\Symmetric\PaddingPluginManager;

class JsonHandler
{
    /**
     * @var string
     */
    public static $file;

    /**
     * @var string
     */
    private static $singleton;

    /**
     * checks according to the path and extension of the file.
     *
     * @return string|void
     */
    private static function checkFile()
    {
        $filePortions = explode(DIRECTORY_SEPARATOR,self::$file);
        $pop = array_pop($filePortions);

        if(file_exists(implode(DIRECTORY_SEPARATOR,$filePortions))
            && preg_match('@[a-zA-Z0-9]+\.json@',$pop)){
            return self::$file;
        }

        exception()->runtime('file path is invalid for json handler');
    }

    /**
     * create if not file exits
     * 
     * @return void
     */
    public static function createIfNotFileExist()
    {
        $file = self::checkFile();

        if(!file_exists($file)){
            files()->put($file,self::encode([]));
        }
    }

    /**
     * makes decode as json the given array
     *
     * @param $data
     * @return array
     */
    public static function decode($data)
    {
        return json_decode($data,1);
    }

    /**
     * delete key from json file
     *
     * @param $key
     * @param null $arrayKey
     * @return bool
     *
     * @throws FileNotFoundException
     */
    public static function delete($key,$arrayKey=null)
    {
        $data = self::get();

        if(is_null($arrayKey)){

            if(isset($data[$key])){
                unset($data[$key]);
                files()->put(self::checkFile(),self::encode($data),true);
                return true;
            }
        }

        // if the data to be deleted
        // in json data contains a nested array data.
        if(!is_null($arrayKey) && is_string($arrayKey)){

            if(isset($data[$key][$arrayKey])){
                unset($data[$key][$arrayKey]);
                files()->put(self::checkFile(),self::encode($data),true);
                return true;
            }
        }

        return false;
    }

    /**
     * makes encode as json the given array
     *
     * @param $data
     * @return false|string
     */
    public static function encode($data)
    {
        return json_encode($data,JSON_PRETTY_PRINT);
    }

    /**
     * get json data
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public static function get($key=null)
    {
        self::createIfNotFileExist();

        $data = self::decode(files()->get(self::checkFile()));
        
        if(is_null($key)){
            return $data;
        }
        
        return $data[$key];
    }

    /**
     * set key value into json file
     *
     * @param $key
     * @param $value
     * @return false|string
     *
     * @throws FileNotFoundException
     */
    public static function set($key,$value)
    {
        self::createIfNotFileExist();
        
        $file = self::get();

        $dotted = explode('.',$key);

        if(count($dotted)>1){
            $arrayInstance = new ArraySafe(self::get());
            $nestedArray = $arrayInstance->set('t.a.b','d')->toArray();
            files()->put(self::checkFile(),self::encode($nestedArray));
        }
        else{

            if(isset($file[$key]) && is_array($value)){
                $file[$key] = array_merge($file[$key],$value);
                files()->put(self::checkFile(),self::encode($file));
            }
            else{
                files()->put(self::checkFile(),self::encode(array_merge($file,[$key=>$value])));
            }
        }

        return self::get();
    }
}