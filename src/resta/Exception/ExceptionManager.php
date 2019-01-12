<?php

namespace Resta\Exception;

use Resta\Support\Utils;
use Resta\Contracts\ExceptionContracts;

class ExceptionManager implements ExceptionContracts {

    public function __construct()
    {
        foreach (debug_backtrace() as $key=>$value){

            appInstance()->register('exceptionFile',debug_backtrace()[1]['file']);
            appInstance()->register('exceptionLine',debug_backtrace()[1]['line']);

            if(isset($value['file'])){
                if(preg_match('@'.core()->url['project'].'@',$value['file'])){

                    appInstance()->terminate('exceptionFile');
                    appInstance()->terminate('exceptionLine');
                    appInstance()->register('exceptionFile',$value['file']);
                    appInstance()->register('exceptionLine',$value['line']);

                    break;
                }
            }
        }
    }

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


        // we will set the information about the exception trace,
        // and then bind it specifically to the event method.
        $customExceptionTrace                       = Utils::trace(1);
        $customExceptionTrace['exception']          = $nameNamespace;
        $customExceptionTrace['callNamespace']      = $callNamespace;
        $customExceptionTrace['parameters']['get']  = get();
        $customExceptionTrace['parameters']['post'] = post();


        // we register the custom exception trace value with the global kernel object.
        appInstance()->register('exceptiontrace',$customExceptionTrace);

        //If the developer wants to execute an event when calling a special exception,
        //we process the event method.
        if(method_exists($callNamespace,'event')){
            $callNamespace->event($customExceptionTrace);
        }

        //throw exception
        throw new $callNamespace();
    }

}