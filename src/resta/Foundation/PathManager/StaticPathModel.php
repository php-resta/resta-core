<?php

namespace Resta\Foundation\PathManager;

use Resta\Support\Utils;
use Resta\Url\UrlVersionIdentifier;

class StaticPathModel extends StaticPathList
{
    /**
     * @param null $group
     * @return mixed|string
     */
    public static function projectPrefix($group=null)
    {
        if($group!==null){
            return self::$projectPrefix.''.DIRECTORY_SEPARATOR.''.$group;
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
    public static function projectPath($prefix,$path)
    {
        return str_replace($prefix.'',"",$path);
    }

    /**
     * @method bootDir
     * @return mixed
     */
    public static function bootDir()
    {
        //get boot directory for application
        return root.'/src/boot';
    }

    /**
     * @method providerDir
     * @return mixed
     */
    public static function providerDir()
    {
        //get boot directory for application
        return root.'/src/providers';
    }

    /**
     * @return string
     */
    public static function appPath()
    {
        //get app path for application
        return self::$appPath=root.''.DIRECTORY_SEPARATOR.''.self::$appDefine.'/app';
    }

    /**
     * @return string
     */
    public static function getEncrypter()
    {
        //get app path for application
        return self::appPath().'/encrypter.yaml';
    }

    /**
     * @return mixed
     */
    public static function getAppStorage()
    {
        if(!defined('app')) define('app',null);

        //get app path for application
        return self::projectPath(self::projectPrefix(),self::appPath().''.DIRECTORY_SEPARATOR.''.self::slashToBackSlash(app).''.self::$storage);
    }

    /**
     * @return string
     */
    public static function getAutoServiceNamespace()
    {
        //get auto service for application
        return ''.self::$store.'\Autoservice';
    }

    /**
     * @return string
     */
    public static function getEnvironmentFile()
    {
        if(defined('app')){

            $appProject=explode("\\",app);
            $app=str_replace("\\","",app);
            $environmentFile=self::appPath().''.DIRECTORY_SEPARATOR.''.strtolower($app).'.yaml';

            if(file_exists($environmentFile)){
                //get app path for application
                return $environmentFile;
            }

            //get app path for application
            return self::appPath().''.DIRECTORY_SEPARATOR.''.strtolower(current($appProject)).'.yaml';
        }
    }

    /**
     * @param null $app
     * @param bool $path
     * @return string
     */
    public static function appVersionRoot($app=null,$path=false)
    {
        $app=($app!==null) ? $app : (defined('app')) ? app : null;

        if($path){

            return self::appPath().''.DIRECTORY_SEPARATOR.''.self::slashToBackSlash($app).''.DIRECTORY_SEPARATOR.''.UrlVersionIdentifier::version().'';
        }
        return self::$autoloadNamespace.'\\'.$app.'\\'.UrlVersionIdentifier::version().'';
    }

    /**
     * @return string
     */
    public static function endpointPath()
    {
        return self::appPath().''.DIRECTORY_SEPARATOR.''.self::slashToBackSlash(app).''.DIRECTORY_SEPARATOR.''.UrlVersionIdentifier::version().''.DIRECTORY_SEPARATOR.''.self::$controller.''.DIRECTORY_SEPARATOR.''.endpoint;
    }

    /**
     * @return array|string
     */
    public static function getServiceConf()
    {
        if(defined('endpoint')){
            return path()->controller(endpoint,true).''.DIRECTORY_SEPARATOR.''.StaticPathModel::$configurationInController.'/ServiceConf.php';
        }
        return [];
    }

    /**
     * @return string
     */
    public static function getServiceDummy()
    {
        return self::endpointPath().''.DIRECTORY_SEPARATOR.''.self::$configurationInController.'/Dummy.yaml';
    }

    /**
     * @param bool $namespace
     * @return string
     */
    public static function appConfig($namespace=false)
    {
        //get app config path for application
        if(false===$namespace){
            return self::appPath().''.DIRECTORY_SEPARATOR.''.self::slashToBackSlash(app).''.DIRECTORY_SEPARATOR.''.UrlVersionIdentifier::version().''.DIRECTORY_SEPARATOR.''.self::$config;
        }
        return self::$autoloadNamespace.'\\'.app.'\\'.UrlVersionIdentifier::version().'\\'.self::$config;
    }

    /**
     * @return array
     */
    public static function httpHeaders()
    {
        //get http header
        /*** @var $httpHeaders \Store\Config\HttpHeaders */
        $httpHeaders=self::$store.'\Config\HttpHeaders';

        //return http headers
        return $httpHeaders::$httpHeaders;
    }

    /**
     * @return string
     */
    public static function appBase()
    {
        return self::appVersionRoot().'\\ServiceBaseController';
    }

    /**
     * @param null $app
     * @param bool $path
     * @return string
     */
    public static function appAnnotation($app=null,$path=false)
    {
        if($path){
            return self::appVersionRoot($app,true).'/ServiceAnnotationsController.php';
        }
        return self::appVersionRoot($app).'\\ServiceAnnotationsController';
    }

    /**
     * @return string
     */
    public static function appMiddleware()
    {
        return self::appVersionRoot().'\\ServiceMiddlewareController';
    }

    /**
     * @param null $app
     * @return string
     */
    public static function appMiddlewarePath($app=null)
    {
        if($app!==null){
            if(!defined('app')){
                define('app',$app);
            }
        }
        return self::$autoloadNamespace.'\\'.app.'\\'.UrlVersionIdentifier::version().'\\'.self::$middleware;
    }

    /**
     * @param null $app
     * @return string
     */
    public static function appSourceEndpoint($app=null)
    {
        return self::appVersionRoot($app).'\\'.self::$optional.'\\'.self::$sourcePath.'\Endpoint';
    }

    /**
     * @param null $app
     * @return string
     */
    public static function appBuilder($app=null)
    {
        return self::appVersionRoot($app).'\\'.self::$model.'\\'.self::$builder;
    }

    /**
     * @return string
     */
    public static function appLog()
    {
        return self::getAppStorage().''.DIRECTORY_SEPARATOR.''.self::$log;
    }

    /**
     * @return string
     */
    public static function appServiceLog()
    {
        return self::appVersionRoot().'\ServiceLogController';
    }

    /**
     * @return string
     */
    public static function storePath()
    {
        return root.'/src/store';
    }

    /**
     * @return string
     */
    public static function storeMigrationPath()
    {
        return self::storePath().'/Migrations';
    }

    /**
     * @param $data
     * @return mixed
     */
    private static function slashToBackSlash($data)
    {
        return str_replace("\\","/",$data);
    }
}