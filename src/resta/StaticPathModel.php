<?php

namespace Resta;

class StaticPathModel extends StaticPathList {

    /**
     * @param null $group
     */
    public static function projectPrefix($group=null){

        if($group!==null){
            return self::$projectPrefix.'/'.$group;
        }

        if(defined('group')){
            return str_replace("\\","/",group);
        }

    }

    /**
     * @param $prefix
     * @param $path
     * @return mixed
     */
    public static function projectPath($prefix,$path){
        return str_replace($prefix.'',"",$path);
    }

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

        if(!defined('app')) define('app',null);

        //get app path for application
        return self::projectPath(self::projectPrefix(),self::appPath().'/'.self::slashToBackSlash(app).''.self::$storage);
    }

    /**
     * @return string
     */
    public static function getAutoServiceNamespace(){

        //get auto service for application
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

            return self::appPath().'/'.self::slashToBackSlash($app).'/'.Utils::getAppVersion($app).'';
        }
        return self::$autoloadNamespace.'\\'.$app.'\\'.Utils::getAppVersion($app).'';
    }

    /**
     * @return string
     */
    public static function endpointPath(){

        return self::appPath().'/'.self::slashToBackSlash(app).'/'.Utils::getAppVersion(app).'/'.self::$controller.'/'.endpoint;
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
            return self::appPath().'/'.self::slashToBackSlash(app).'/'.Utils::getAppVersion(app).'/'.self::$config;
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

    /**
     * @return string
     */
    public static function appBase(){
        return self::appVersionRoot().'\\ServiceBaseController';
    }

    /**
     * @param null $app
     * @param bool $path
     * @return string
     */
    public static function appAnnotation($app=null,$path=false){

        if($path){
            return self::appVersionRoot($app,true).'/ServiceAnnotationsController.php';
        }
        return self::appVersionRoot($app).'\\ServiceAnnotationsController';
    }

    /**
     * @return string
     */
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

    /**
     * @param null $app
     * @return string
     */
    public static function appRepository($app=null){
        return 'asa';
    }

    /**
     * @param null $app
     * @return string
     */
    public static function appSourceEndpoint($app=null){
        return self::appVersionRoot($app).'\\'.self::$optional.'\\'.self::$sourcePath.'\Endpoint';
    }

    /**
     * @param null $app
     * @return string
     */
    public static function appBuilder($app=null){
        return self::appVersionRoot($app).'\\'.self::$model.'\\'.self::$builder;
    }

    /**
     * @return string
     */
    public static function appLog(){
        return self::getAppStorage().'/'.self::$log;
    }

    /**
     * @return string
     */
    public static function appServiceLog(){
        return self::appVersionRoot().'\ServiceLogController';
    }

    public static function storePath()
    {
        return root.'/src/store';
    }

    public static function storeMigrationPath()
    {
        return self::storePath().'/Migrations';
    }

    /**
     * @param $data
     */
    private static function slashToBackSlash($data){
        return str_replace("\\","/",$data);
    }


}