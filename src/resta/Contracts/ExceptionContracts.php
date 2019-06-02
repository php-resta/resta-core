<?php

namespace Resta\Contracts;

interface ExceptionContracts {

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function invalidArgument($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function badFunctionCall($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function badMethodCall($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function domain($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function length($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function logic($msg=null);

    /**
     * @return mixed
     */
    public function notFoundException();

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function outOfRange($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function overflow($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function range($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function runtime($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function underflow($msg=null);

    /**
     * @param null|string $msg
     * @return mixed
     */
    public function unexpectedValue($msg=null);
}