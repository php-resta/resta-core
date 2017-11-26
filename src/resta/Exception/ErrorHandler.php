<?php

namespace Resta\Exception;

use Resta\ApplicationProvider;
use Resta\Response\ResponseApplication;
use Resta\StaticPathModel;

class ErrorHandler extends ApplicationProvider {

    public function handle(){

        set_error_handler([$this,'setErrorHandler']);
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

        //get App Exception Config Class
        $exception=StaticPathModel::$store.'\Config\Exception';

        $appExceptionSuccess=['success'=>false];

        $errType='Undefined';
        if(preg_match('@(.*?):@is',$errStr,$errArr)){
            $errType=trim(str_replace('Uncaught','',$errArr[1]));
        }

        if(preg_match('@(.*?):(.*?)in.\/@is',$errStr,$errStrRealArray)){
            $errStrReal=trim($errStrRealArray[2]);
        }

        if($errType==="Undefined"){
            $errStrReal=$errStr;
        }
        else{
            $errContext['trace']=$errStr;
        }

        $appException=$appExceptionSuccess+$exception::handler($errNo,$errStrReal,$errFile,$errLine,$errType,$errContext);

        //set json app exception
        $this->app->kernel()->responseSuccess=false;
        $this->app->kernel()->responseStatus=$exception::exceptionTypeCodes($errType);
        $this->app->kernel()->router=$appException;
        echo $this->app->kernel()->out->handle();
        exit();


    }


    /**
     * @method fatalErrorShutdownHandler
     */
    public function fatalErrorShutdownHandler(){

        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR) {
            // fatal error
            $this->setErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line'],[]);
        }
    }

}