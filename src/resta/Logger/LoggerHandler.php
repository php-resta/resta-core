<?php

namespace Resta\Logger;

use Psr\Log\LoggerInterface;
use Resta\Support\ClosureDispatcher;

class LoggerHandler implements LoggerInterface
{
    /**
     * @var string
     */
    protected $logger;

    /**
     * @var null
     */
    protected $file;

    /**
     * @var string
     */
    protected $adapter;

    /**
     * LoggerHandler constructor.
     * @param null $file
     */
    public function __construct($file=null)
    {
        $this->file = $file;
        $this->logger = core()->loggerService ?? null;
    }

    /**
     * @param $adapter
     * @return $this
     */
    public function adapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a message to the logs.
     *
     * @param  mixed  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = []) {

    }

    /**
     * Log a notice to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Write a message to the log.
     *
     * @param mixed $level
     * @param string $message
     * @param $context
     * @return void
     */
    protected function writeLog($level, $message,$context)
    {
        $file = ($this->file===null) ? $level : $this->file;

        if($this->adapter!==null){

            $adapter = $this->adapter;

            ClosureDispatcher::bind($this->logger)->call(function() use($adapter){
                return $this->adapter = $adapter;
            });
        }

        if($this->logger!==null){
            $this->logger->logHandler($message,$file,$level);
        }

        return $context;

    }
}