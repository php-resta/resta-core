<?php

namespace Resta\Exception;

use Resta\StaticPathModel;
use Resta\ApplicationProvider;

class ErrorHandler extends ApplicationProvider {

    /**
     * @method handle
     * return void
     */
    public function handle(){

        //This function can be used for defining your own way of handling errors during runtime,
        //for example in applications in which you need to do cleanup of data/files when a critical error happens,
        //or when you need to trigger an error under certain conditions (using trigger_error()).
        set_error_handler([$this,'setErrorHandler']);

        //Registers a callback to be executed after script execution finishes or exit() is called.
        //Multiple calls to register_shutdown_function() can be made, and each will be called in the same order as
        //they were registered. If you call exit() within one registered shutdown function,
        //processing will stop completely and no other registered shutdown functions will be called.
        register_shutdown_function([$this,'fatalErrorShutdownHandler']);
    }

    /**
     * @param $errNo null
     * @param $errStr null
     * @param $errFile null
     * @param $errLine null
     * @param $errContext null
     * @return mixed
     */
    public function setErrorHandler($errNo=null, $errStr=null, $errFile=null, $errLine=null, $errContext=null){

        /**
         * @var $exception \Store\Config\Exception
         * get App Exception Config Class
         */
        $exception=StaticPathModel::$store.'\Config\Exception';

        //constant object
        $errType='Undefined';
        $errStrReal='';

        //Catch exception via preg match
        if(preg_match('@(.*?):@is',$errStr,$errArr)){
            $errType=trim(str_replace('Uncaught','',$errArr[1]));
        }

        $errStrReal=$errStr;

        if(preg_match('@Uncaught@is',$errStr) && preg_match('@(.*?):(.*?)\sin\s@is',$errStr,$errStrRealArray)){
            $errStrReal=trim($errStrRealArray[2]);
        }

        if($errType==="Undefined"){
            $errStrReal=$errStr;
        }
        else{
            $errContext['trace']=$errStr;
        }

        $status=$exception::exceptionTypeCodes($errType);

        $optionalException=str_replace("\\","\\\\",$this->app->namespace()->optionalException());

        if(preg_match('@'.$optionalException.'@is',$errType)){

            //linux test
            $trace=$errContext['trace'];
            if(preg_match('@Stack trace:\n#0(.*)\n#1@is',$trace,$traceArray)){
                $traceFile=str_replace(root,'',$traceArray[1]);
                if(preg_match('@(.*)\((\d+)\)@is',$traceFile,$traceResolve)){
                    $errFile=$traceResolve[1];
                    $errLine=(int)$traceResolve[2];
                }
            }
            $instanceErrtype=new $errType;
            $status=$exception::exceptionTypeCodes(current(class_parents($instanceErrtype)));

            $errType=class_basename($errType);
        }

        //set as the success object is false
        $appExceptionSuccess=['success'=>(bool)false,'status'=>$status];

        $environment='local';

        if(isset($this->app->kernel()->applicationKey)){

            //finally,set object for exception
            $environment=($this->app->kernel()->applicationKey===null) ? 'production' : environment();
        }

        $appException=$appExceptionSuccess+$exception::$environment($errNo,$errStrReal,$errFile,$errLine,$errType,$errContext);

        //set json app exception
        $this->app->kernel()->router=$appException;
        echo $this->app->kernel()->out->handle();
        exit();


    }


    /**
     * @method fatalErrorShutdownHandler
     */
    public function fatalErrorShutdownHandler(){

        $last_error = error_get_last();

        if($last_error!==null){

            // fatal error
            $this->setErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line'],[]);

        }


    }

}