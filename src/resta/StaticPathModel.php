<?php

namespace Resta;

class StaticPathModel extends StaticPathList {

    /**
     * @method bootDir
     * @return mixed
     */
    public static function bootDir(){

        //get boot directory for application
        return root.'/src/boot';
    }


    /**
     * @method appPath
     * @return mixed
     */
    public static function appPath(){

        //get app path for application
        return self::$appPath=root.'/'.self::$appDefine.'/app';
    }

    /**
     * @method getEncrypter
     * @return mixed
     */
    public static function getEncrypter(){

        //get app path for application
        return self::appPath().'/encrypter.yaml';
    }

    /**
     * @method getAppStorage
     * @return mixed
     */
    public static function getAppStorage(){

        //get app path for application
        return self::appPath().'/'.app.'/Storage';
    }

    public static function getAutoServiceNamespace(){

        return ''.self::$store.'\Autoservice';
    }



    /**
     * @method getEnvironmentFile
     * @return mixed
     */
    public static function getEnvironmentFile(){

        if(defined('app')){

            $appProject=explode("\\",app);
            $app=str_replace("\\","",app);
            $environmentFile=self::appPath().'/'.strtolower($app).'.yaml';

            if(file_exists($environmentFile)){
                //get app path for application
                return $environmentFile;
            }

            //get app path for application
            return self::appPath().'/'.strtolower(current($appProject)).'.yaml';

        }

    }

    /**
     * @method appVersionRoot
     * @return string
     */
    public static function appVersionRoot($app=null,$path=false){

        $app=($app!==null) ? $app : (defined('app')) ? app : null;

        if($path){

            return self::appPath().'/'.$app.'/'.Utils::getAppVersion($app).'';
        }
        return self::$autoloadNamespace.'\\'.$app.'\\'.Utils::getAppVersion($app).'';
    }

    public static function endpointPath(){

        return self::appPath().'/'.app.'/'.Utils::getAppVersion(app).'/'.self::$controller.'/'.endpoint;
    }

    /**
     * @method getServiceConf
     * @return string
     */
    public static function getServiceConf(){

        return self::endpointPath().'/ServiceConf.php';
    }

    /**
     * @method getServiceDummy
     * @return string
     */
    public static function getServiceDummy(){

        return self::endpointPath().'/Dummy.yaml';
    }

    /**
     * @var $namespace
     * @method appConfig
     * @return string
     */
    public static function appConfig($namespace=false){

        //get app config path for application
        if(false===$namespace){
            return self::appPath().'/'.app.'/'.Utils::getAppVersion(app).'/'.self::$config;
        }
        return self::$autoloadNamespace.'\\'.app.'\\'.Utils::getAppVersion(app).'\\'.self::$config;
    }

    /**
     * @return mixed
     */
    public static function httpHeaders(){

        //get http header
        /*** @var $httpHeaders \Store\Config\HttpHeaders */
        $httpHeaders=self::$store.'\Config\HttpHeaders';

        //return http headers
        return $httpHeaders::$httpHeaders;
    }

    public static function appBase(){
        return self::appVersionRoot().'\\ServiceBaseController';
    }

    public static function appAnnotation($app=null,$path=false){

        if($path){
            return self::appVersionRoot($app,true).'/ServiceAnnotationsController.php';
        }
        return self::appVersionRoot($app).'\\ServiceAnnotationsController';
    }

    public static function appMiddleware(){
        return self::appVersionRoot().'\\ServiceMiddlewareController';
    }

    /**
     * @method appMiddlewarePath
     * @return string
     */
    public static function appMiddlewarePath($app=null){

        if($app!==null){
            if(!defined('app')){
                define('app',$app);
            }

        }

        return self::$autoloadNamespace.'\\'.app.'\\'.Utils::getAppVersion(app).'\\'.self::$middleware;
    }

    public static function appRepository($app=null){
        return self::appVersionRoot($app).'\\'.self::$optional.'\\'.self::$repository;
    }

    public static function appSourceEndpoint($app=null){
        return self::appVersionRoot($app).'\\'.self::$optional.'\\'.self::$sourcePath.'\Endpoint';
    }

    public static function appBuilder($app=null){
        return self::appVersionRoot($app).'\\'.self::$model.'\\'.self::$builder;
    }

    public static function appLog(){
        return self::getAppStorage().'/'.self::$log;
    }

    public static function appServiceLog(){
        return self::appVersionRoot().'\ServiceLogController';
    }

}