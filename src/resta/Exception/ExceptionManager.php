<?php

namespace Resta\Exception;

use Resta\Contracts\ExceptionContracts;

class ExceptionManager extends ExceptionTrace implements ExceptionContracts {

    /**
     * @var array
     */
    protected $data=[];

    /**
     * @param null $msg
     */
    public function invalidArgument($msg=null){
        throw new \InvalidArgumentException($msg);
    }

    /**
     * @param null $msg
     */
    public function badFunctionCall($msg=null){
        throw new \BadFunctionCallException($msg);
    }

    /**
     * @param null $msg
     */
    public function badMethodCall($msg=null){
        throw new \BadMethodCallException($msg);
    }

    /**
     * @param null $msg
     */
    public function domain($msg=null){
        throw new \DomainException($msg);
    }

    /**
     * @param null $msg
     */
    public function length($msg=null){
        throw new \LengthException($msg);
    }

    /**
     * @param null $msg
     */
    public function logic($msg=null){
        throw new \LogicException($msg);
    }

    /**
     * @param null $msg
     */
    public function outOfRange($msg=null){
        throw new \OutOfRangeException($msg);
    }

    /**
     * @param null $msg
     */
    public function overflow($msg=null){
        throw new \OverflowException($msg);
    }

    /**
     * @param null $msg
     */
    public function range($msg=null){
        throw new \RangeException($msg);
    }

    /**
     * @param null $msg
     */
    public function runtime($msg=null){
        throw new \RuntimeException($msg);
    }

    /**
     * @param null $msg
     */
    public function underflow($msg=null){
        throw new \UnderflowException($msg);
    }

    /**
     * @param null $msg
     */
    public function unexpectedValue($msg=null){
        throw new \UnexpectedValueException($msg);
    }
}