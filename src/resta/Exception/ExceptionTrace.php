<?php

namespace Resta\Exception;

use Resta\Support\Utils;
use Resta\Support\Dependencies;
use Resta\Foundation\ApplicationProvider;

class ExceptionTrace extends ApplicationProvider
{
    /**
     * ExceptionTrace constructor.
     * @param $app
     * @param null $name
     * @param array $params
     */
    public function __construct($app,$name=null,$params=array())
    {
        parent::__construct($app);

        // we help the user to pull a special message from
        // the translate section to be specified by the user for the exception.
        $this->exceptionTranslate($name,$params);

        // for real file path with
        // debug backtrace method are doing follow.
        $this->debugBackTrace();
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
                $this->app->register('exceptionTranslateParams',$name,$params);
            }
            $this->app->register('exceptionTranslate',$name);
        }
    }

    /**
     * get debug backtrace for exception
     *
     * @return mixed|void
     */
    public function debugBackTrace()
    {
        foreach (debug_backtrace() as $key=>$value){

            if(isset(debug_backtrace()[$key],debug_backtrace()[$key]['file']))
            {
                $this->app->register('exceptionFile',debug_backtrace()[$key]['file']);
                $this->app->register('exceptionLine',debug_backtrace()[$key]['line']);
            }

            Dependencies::loadBootstrapperNeedsForException();

            if(isset($value['file']) && isset(core()->urlComponent)){
                if(preg_match('@'.core()->urlComponent['project'].'|boot|providers@',$value['file'])){

                    $this->app->terminate('exceptionFile');
                    $this->app->terminate('exceptionLine');
                    $this->app->register('exceptionFile',$value['file']);
                    $this->app->register('exceptionLine',$value['line']);

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
        $nameNamespace = app()->namespace()->exception().'\\'.$nameException;

        // first, you are looking for an exception
        // in the application directory class.
        if(Utils::isNamespaceExists($nameNamespace)){
            $callNamespace = new $nameNamespace;
        }
        else{

            // if you do not have an exception in the application directory,
            // this time we are looking for an exception in the core directory.
            $nameNamespace = __NAMESPACE__.'\\'.$nameException;
            if(Utils::isNamespaceExists($nameNamespace)){
                $callNamespace = new $nameNamespace;
            }
        }

        if(isset($callNamespace)){

            // we will set the information about the exception trace,
            // and then bind it specifically to the event method.
            $customExceptionTrace                       = Utils::trace(1);
            $customExceptionTrace['exception']          = $nameNamespace;
            $customExceptionTrace['callNamespace']      = $callNamespace;
            $customExceptionTrace['parameters']['get']  = get();
            $customExceptionTrace['parameters']['post'] = post();


            // we register the custom exception trace value with the global kernel object.
            $this->app->register('exceptiontrace',$customExceptionTrace);

            //If the developer wants to execute an event when calling a special exception,
            //we process the event method.
            if(method_exists($callNamespace,'event')){
                $callNamespace->event($customExceptionTrace);
            }

            //throw exception
            throw new $callNamespace();
        }
    }
}