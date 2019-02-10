<?php

namespace Resta\Logger;

use Resta\Support\Utils;
use Resta\GlobalLoaders\Logger as LoggerGlobalInstance;

class LoggerService
{
    /**
     * @var $adapter
     */
    protected $adapter;

    /**
     * @param $printer
     * @param callable $callback
     * @return mixed
     */
    public function checkLoggerConfiguration($printer,callable $callback)
    {
        // logger service handler
        if(config('app.logger')){
            return $this->logHandler($printer,'access',$this->getLoggerType());
        }

        //return closure object with printer
        return call_user_func_array($callback,[$printer]);
    }

    /**
     * @return string
     */
    private function getLoggerType()
    {
        return (appInstance()->getSuccess()) ? 'info' : 'error';
    }

    /**
     * @param LoggerGlobalInstance $logger
     */
    public function handle(LoggerGlobalInstance $logger)
    {
        //set define for logger
        define('logger',true);

        //we get the logger namespace value.
        $loggerNamespace = app()->namespace()->logger();

        // if the logger file does not exist
        // or request console is true
        // we throw a domain exception.
        if(Utils::isNamespaceExists($loggerNamespace)===false){

            //get checking console for logger
            if(Utils::isRequestConsole()===false && core()->isAvailableStore){

                //throw exception via domain method
                exception()->domain('Such a group was not created within the project.');
            }

            return false;
        }

        //We are getting the path to
        //the service log file in the project's version directory.
        $appBase = app()->makeBind($loggerNamespace);

        // we send the resulting adapter property as
        // a reference to the bind automatic instance class.
        $logger->setLogger($appBase,$appBase->adapter,$this);
    }

    /**
     * @param $printer
     * @param string $file
     * @param string $type
     * @return mixed
     */
    public function logHandler($printer,$file="access",$type='info')
    {
        if(isset(core()->log)){

            //we get the log object that was previously assigned.
            $log = core()->log;

            $base = current($log);

            if($this->adapter!==null){

                $log = [];
                $log[$this->adapter] = $base;
            }

            // this object is obtained directly as an array and specifies
            // the adapter value for the first key log. The value of the directory stores
            // the instance value of the service log class. From there,
            // we call the method specified by the adapter in the service log class
            // and log the application in the customized mode for the application.
            // The service log class uses the monolog class.
            if(method_exists($base,$adapter=key($log))){
                call_user_func_array([$base,$adapter],[$printer,$file,$type]);
            }

            //printer back
            return $printer;
        }

    }
}