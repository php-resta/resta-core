<?php

namespace Resta\Exception;

use LogicException;
use RangeException;
use DomainException;
use LengthException;
use RuntimeException;
use OverflowException;
use UnderflowException;
use OutOfRangeException;
use BadMethodCallException;
use UnexpectedValueException;
use BadFunctionCallException;
use InvalidArgumentException;
use Resta\Contracts\ExceptionContracts;

/**
 * @method notFound($name,$arguments=array())
 * @method fileNotFound($name,$arguments=array())
 */
class ExceptionManager extends ExceptionTrace implements ExceptionContracts
{
    /**
     * invalid argument exception
     *
     * @param null|string $msg
     */
    public function invalidArgument($msg=null)
    {
        throw new InvalidArgumentException($msg);
    }

    /**
     * bad function call exception
     *
     * @param null|string $msg
     */
    public function badFunctionCall($msg=null)
    {
        throw new BadFunctionCallException($msg);
    }

    /**
     * bad method call
     *
     * @param null|string $msg
     */
    public function badMethodCall($msg=null)
    {
        throw new BadMethodCallException($msg);
    }

    /**
     * domain exception
     *
     * @param null|string $msg
     */
    public function domain($msg=null)
    {
        throw new DomainException($msg);
    }

    /**
     * length exception
     *
     * @param null|string $msg
     */
    public function length($msg=null)
    {
        throw new LengthException($msg);
    }

    /**
     * logic exception
     *
     * @param null|string $msg
     */
    public function logic($msg=null)
    {
        throw new LogicException($msg);
    }

    /**
     * not found exception
     *
     * @param null|string $msg
     * @return null
     *
     */
    public function notFoundException($msg=null)
    {
        return $this->notFound($msg);
    }

    /**
     * file not found exception
     *
     * @param null|string $msg
     * @return mixed|void
     *
     */
    public function fileNotFoundException($msg=null)
    {
        return $this->fileNotFound($msg);
    }

    /**
     * out of range exception
     *
     * @param null|string $msg
     */
    public function outOfRange($msg=null)
    {
        throw new OutOfRangeException($msg);
    }

    /**
     * overflow exception
     *
     * @param null|string $msg
     */
    public function overflow($msg=null)
    {
        throw new OverflowException($msg);
    }

    /**
     * range exception
     *
     * @param null|string $msg
     */
    public function range($msg=null)
    {
        throw new RangeException($msg);
    }

    /**
     * runtime exception
     *
     * @param null|string $msg
     */
    public function runtime($msg=null)
    {
        throw new RuntimeException($msg);
    }

    /**
     * underflow exception
     *
     * @param null|string $msg
     */
    public function underflow($msg=null)
    {
        throw new UnderflowException($msg);
    }

    /**
     * unexpexted value exception
     *
     * @param null|string $msg
     */
    public function unexpectedValue($msg=null)
    {
        throw new UnexpectedValueException($msg);
    }
}