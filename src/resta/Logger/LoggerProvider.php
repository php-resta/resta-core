<?php

namespace Resta\Logger;

use Resta\Support\Utils;
use Resta\Contracts\HandleContracts;
use Resta\Foundation\ApplicationProvider;

class LoggerProvider extends ApplicationProvider implements HandleContracts
{
    /**
     * @var $adapter
     */
    protected $adapter;

    /**
     * check logger configuration
     *
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
     * get logger type
     *
     * @return string
     */
    private function getLoggerType()
    {
        return ($this->app['responseSuccess']) ? 'info' : 'error';
    }

    /**
     * logger application handle
     *
     * @return mixed|void
     */
    public function handle()
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
            $appBase = $this->app->resolve($loggerNamespace);

            // we send the resulting adapter property as
            // a reference to the bind automatic instance class.
            $this->setLogger($appBase,$appBase->adapter,$this);
        }

    }

    /**
     * logger handler for application
     *
     * @param $printer
     * @param string $file
     * @param string $type
     * @return mixed
     */
    public function logHandler($printer,$file="access",$type='info')
    {
        if(isset($this->app['log'])){

            //we get the log object that was previously assigned.
            $log = $this->app['log'];

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
                $logOutput = (isset($this->app['productionLogMessage'])) ?
                    $this->app['productionLogMessage'] :
                    $printer;

                call_user_func_array([$base,$adapter],[$logOutput,$file,$type]);
            }

            //printer back
            return $printer;
        }

    }

    /**
     * register to container for logger
     *
     * @param mixed ...$params
     */
    public function setLogger(...$params){

        // params list
        [$base,$adapter,$loggerService] = $params;

        // we take the adapter attribute for the log log
        // from the service log class and save it to the kernel object.
        $this->app->register('logger',app()->namespace()->logger());
        $this->app->register('loggerService',$loggerService);
        $this->app->register('log',$adapter,$base);
    }
}