<?php

namespace Resta\Logger;

use Resta\Support\Utils;

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
        return (core()->responseSuccess) ? 'info' : 'error';
    }

    /**
     * @param LoggerKernelAssigner $logger
     */
    public function handle(LoggerKernelAssigner $logger)
    {
        //set define for logger
        define('logger',true);

        //we get the logger namespace value.
        $loggerNamespace = app()->namespace()->logger();

        // if the logger file does not exist
        // or request console is true
        // we throw a domain exception.
        if(Utils::isNamespaceExists($loggerNamespace)){

            //We are getting the path to
            //the service log file in the project's version directory.
            $appBase = app()->makeBind($loggerNamespace);

            // we send the resulting adapter property as
            // a reference to the bind automatic instance class.
            $logger->setLogger($appBase,$appBase->adapter,$this);
        }

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

                // this is very important.
                // in the production log messages,
                // we have to get the production log message kernel variable
                // in order not to show an external error to the user
                $logOutput = (isset(core()->productionLogMessage)) ?
                    core()->productionLogMessage :
                    $printer;

                call_user_func_array([$base,$adapter],[$logOutput,$file,$type]);
            }

            //printer back
            return $printer;
        }

    }
}