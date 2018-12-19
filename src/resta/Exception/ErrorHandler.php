<?php

namespace Resta\Exception;

use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\StaticPathModel;
use Resta\ClosureDispatcher;
use Resta\ApplicationProvider;

class ErrorHandler extends ApplicationProvider {

    /**
     * @var $lang
     */
    public $lang = null;

    /**
     * @var $exception
     */
    protected $exception;

    /**
     * @var $data array
     */
    protected $data=array();

    /**
     * @return mixed|string
     */
    private function getEnvironmentStatus(){

        $environment=environment();

        if(isset($this->app->kernel()->applicationKey)){

            // application key, but if it has a null value
            // then we move the environment value to the production environment.
            $applicationKey = $this->app->kernel()->applicationKey;
            $environment    = ($applicationKey===null) ? 'production' : environment();
        }

        return $environment;
    }

    /**
     * @return void|mixed
     */
    private function getStatusFromContext(){

        $exception=$this->exception;

        $this->data['status']=$exception::exceptionTypeCodes($this->data['errType']);

        $this->terminate('responseSuccess');
        $this->terminate('responseStatus');
        $this->register('responseSuccess',(bool)false);
        $this->register('responseStatus',$this->data['status']);

        $optionalException=str_replace("\\","\\\\",$this->app->namespace()->optionalException());

        if(preg_match('@'.$optionalException.'@is',$this->data['errType'])){

            //linux test
            $trace=$this->data['errContext']['trace'];
            if(preg_match('@Stack trace:\n#0(.*)\n#1@is',$trace,$traceArray)){
                $traceFile=str_replace(root,'',$traceArray[1]);
                if(preg_match('@(.*)\((\d+)\)@is',$traceFile,$traceResolve)){
                    $this->data['errFile']=$traceResolve[1];
                    $this->data['errLine']=(int)$traceResolve[2];
                }
            }
            $instanceErrtype=new $this->data['errType'];
            $this->data['status']=$exception::exceptionTypeCodes(current(class_parents($instanceErrtype)));

            $this->data['errType']=class_basename($this->data['errType']);
        }
    }

    /**
     * @method handle
     * return void
     */
    public function handle()
    {
        //sets which php errors are reported
        error_reporting(0);

        // in general we will use the exception class
        // in the store/config directory to make it possible
        // to change the user-based exceptions.
        $this->exception=StaticPathModel::$store.'\Config\Exception';

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
     * @param $this->data['errFile'] null
     * @param $this->data['errLine'] null
     * @param $this->data['errContext'] null
     * @return mixed
     */
    public function setErrorHandler($errNo=null, $errStr=null, $errFile=null, $errLine=null, $errContext=null)
    {
        // in general we will use the exception class
        // in the store/config directory to make it possible
        // to change the user-based exceptions.
        $exception=$this->exception;

        //constant object as default
        $this->data['errType']              = 'Undefined';
        $this->data['errStrReal']           = $errStr;
        $this->data['errorClassNamespace']  = null;
        $this->data['errFile']              = $errFile;
        $this->data['errLine']              = $errLine;

        // catch exception via preg match
        // and then clear the Uncaught statement from inside.
        $this->getUncaughtProcess();

        $this->getStatusFromContext();

        if(is_array($meta=config('response.meta'))){

            //set as the success object is false
            $appExceptionSuccess=[];
        }
        else{

            //set as the success object is false
            $appExceptionSuccess=['success'=>(bool)false,'status'=>$this->data['status']];
        }

        //get lang message for exception
        $this->getLangMessageForException();

        if(property_exists(resta(),'exceptiontrace')){

            $customExceptionTrace=resta()->exceptiontrace;

            $this->data['errFile']=$customExceptionTrace['file'];
            $this->data['errLine']=$customExceptionTrace['line'];
        }

        $environment=$this->getEnvironmentStatus();
        $appException=$appExceptionSuccess+$exception::$environment(
                $errNo,
                $this->data['errStrReal'],
                $this->data['errFile'],
                $this->data['errLine'],
                $this->data['errType'],
                $this->data['lang']
            );


        //Get or Set the HTTP response code
        http_response_code($this->data['status']);

        //set json app exception
        resta()->router=$appException;

        $restaOutHandle=resta()->out->handle();


        if($restaOutHandle===null){
            echo json_encode($appException);
            exit();
        }
        else{
            echo $restaOutHandle;
            exit();
        }

    }

    /**
     * @method fatalErrorShutdownHandler
     */
    public function fatalErrorShutdownHandler()
    {
        //get fatal error
        $last_error =error_get_last();

        if($last_error!==null){

            if(!defined('methodName')){

                define('methodName',null);
            }

            if(isset(resta()->exceptionFile)){
                $last_error['file'] = resta()->exceptionFile;
                $last_error['line'] = resta()->exceptionLine;
            }
            header('Content-type:application/json;charset=utf-8');

            $this->setErrorHandler(
                E_ERROR,
                $last_error['message'],
                $last_error['file'],
                $last_error['line'],
                []
            );
        }
    }

    /**
     * @return void|mixed
     */
    private function getLangMessageForException(){

        $clone = clone $this;

        if(class_exists($this->data['errorClassNamespace'])
            && Str::startsWith($this->data['errorClassNamespace'],'App')){

            ClosureDispatcher::bind($this->data['errorClassNamespace'])->call(function() use ($clone) {
                if(property_exists($this,'lang')){
                    $clone->lang=$this->lang;
                }
            });
        }

        $this->data['lang']=$lang=$clone->lang;

        if($lang!==null){
            $langMessage=trans('exception.'.$lang);
        }
        else{
            $langMessage=null;
        }


        if($langMessage!==null){
            $this->data['errStrReal']=$langMessage;
        }
    }

    /**
     * @return void|mixed
     */
    private function getUncaughtProcess(){

        // catch exception via preg match
        // and then clear the Uncaught statement from inside.
        if(preg_match('@(.*?):@is',$this->data['errStrReal'],$errArr)){

            $this->data['errType']=trim(str_replace('Uncaught','',$errArr[1]));
            $this->data['errorClassNamespace']=$this->data['errType'];
        }

        if(preg_match('@Uncaught@is',$this->data['errStrReal'])
            && preg_match('@(.*?):(.*?)\sin\s@is',$this->data['errStrReal'],$errStrRealArray)){
            $this->data['errStrReal']=trim($errStrRealArray[2]);
        }

        if($this->data['errType']==="Undefined"){
            $this->data['errStrReal']=$this->data['errStrReal'];
        }
        else{
            $this->data['errContext']['trace']=$this->data['errStrReal'];
        }
    }

}