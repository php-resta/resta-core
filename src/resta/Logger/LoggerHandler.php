<?php

namespace Resta\Logger;

use Psr\Log\LoggerInterface;

class LoggerHandler implements LoggerInterface {

    /**
     * @var $logger
     */
    protected $logger;

    /**
     * @var $file
     */
    protected $file;

    /**
     * LoggerHandler constructor.
     */
    public function __construct($file=null) {

        $this->file=$file;
        $this->logger=app()->singleton()->loggerService;
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function emergency($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
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
    public function notice($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  mixed  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = []) {

        return $this->writeLog(__FUNCTION__,$message,$context);
    }

    /**
     * Write a message to the log.
     *
     * @param  mixed  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    protected function writeLog($level, $message, $context)
    {
        $file=($this->file===null) ? $level : $this->file;

        $this->logger->logHandler($message,$file,$level);
    }
}