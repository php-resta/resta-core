<?php

namespace Resta\Exception;

use Resta\Contracts\ExceptionContracts;

class ExceptionManager implements ExceptionContracts {

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

    /**
     * @param $name
     */
    public function __get($name)
    {
        //We use the magic method for the exception and
        //call the exception class in the application to get the instance.
        $nameException=ucfirst($name).'Exception';
        $nameNamespace=app()->namespace()->optionalException().'\\'.$nameException;
        $callNamespace=new $nameNamespace;

        //If the developer wants to execute an event when calling a special exception,
        //we process the event method.
        if(method_exists($callNamespace,'event')){
            $callNamespace->event();
        }

        //throw exception
        throw new $callNamespace();
    }

}