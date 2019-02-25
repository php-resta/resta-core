<?php

namespace Resta\Exception;

use Resta\Support\Utils;
use Resta\Support\BootLoaderNeeds;

class ExceptionTrace {

    /**
     * ExceptionManager constructor.
     * @param null $name
     * @param array $params
     */
    public function __construct($name=null,$params=array())
    {
        // we help the user to pull a special message from
        // the translate section to be specified by the user for the exception.
        $this->exceptionTranslate($name,$params);

        // for real file path with
        // debug backtrace method are doing follow.
        $this->debubBackTrace();
    }

    /**
     * get exception translate params
     *
     * @param $name
     * @param array $params
     */
    private function exceptionTranslate($name,$params=array())
    {
        if($name!==null){
            if(count($params)){
                app()->register('exceptionTranslateParams',$name,$params);
            }
            app()->register('exceptionTranslate',$name);
        }
    }

    /**
     * get debug backtrace for exception
     *
     * @return mixed|void
     */
    public function debubBackTrace()
    {
        foreach (debug_backtrace() as $key=>$value){

            app()->register('exceptionFile',debug_backtrace()[1]['file']);
            app()->register('exceptionLine',debug_backtrace()[1]['line']);

            BootLoaderNeeds::loadNeeds();

            if(isset($value['file']) && isset(core()->url)){
                if(preg_match('@'.core()->url['project'].'@',$value['file'])){

                    app()->terminate('exceptionFile');
                    app()->terminate('exceptionLine');
                    app()->register('exceptionFile',$value['file']);
                    app()->register('exceptionLine',$value['line']);

                    break;
                }
            }
        }
    }

    /**
     * @param $name
     */
    public function __get($name)
    {
        //We use the magic method for the exception and
        //call the exception class in the application to get the instance.
        $nameException = ucfirst($name).'Exception';
        $nameNamespace = app()->namespace()->optionalException().'\\'.$nameException;
        $callNamespace = new $nameNamespace;


        // we will set the information about the exception trace,
        // and then bind it specifically to the event method.
        $customExceptionTrace                       = Utils::trace(1);
        $customExceptionTrace['exception']          = $nameNamespace;
        $customExceptionTrace['callNamespace']      = $callNamespace;
        $customExceptionTrace['parameters']['get']  = get();
        $customExceptionTrace['parameters']['post'] = post();


        // we register the custom exception trace value with the global kernel object.
        app()->register('exceptiontrace',$customExceptionTrace);

        //If the developer wants to execute an event when calling a special exception,
        //we process the event method.
        if(method_exists($callNamespace,'event')){
            $callNamespace->event($customExceptionTrace);
        }

        //throw exception
        throw new $callNamespace();
    }
}