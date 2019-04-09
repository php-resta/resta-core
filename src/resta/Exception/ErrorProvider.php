<?php

namespace Resta\Exception;

use Resta\Support\Str;
use Resta\Support\Utils;
use Resta\Support\Dependencies;
use Resta\Support\ClosureDispatcher;
use Resta\Foundation\ApplicationProvider;
use Resta\Foundation\PathManager\StaticPathModel;

class ErrorProvider extends ApplicationProvider {

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

        // application key, but if it has a null value
        // then we move the environment value to the production environment.
        return $this->app->detectEnvironmentForApplicationKey();
    }

    /**
     * @return void|mixed
     */
    private function getStatusFromContext(){

        $exception=$this->exception;

        if(isset(core()->exceptiontrace))
        {
            $this->data['status'] = (int)core()->exceptiontrace['callNamespace']->getCode();
        }
        else {

            $this->data['status']=(int)$exception::exceptionTypeCodes($this->data['errType']);
        }

        $this->app->terminate('responseSuccess');
        $this->app->terminate('responseStatus');
        $this->app->register('responseSuccess',(bool)false);
        $this->app->register('responseStatus',$this->data['status']);


        $optionalException=str_replace("\\","\\\\",$this->app->namespace()->exception());

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
     * @param null $errNo
     * @param null $errStr
     * @param null $errFile
     * @param null $errLine
     * @param null $errContext
     */
    public function setErrorHandler($errNo=null, $errStr=null, $errFile=null, $errLine=null, $errContext=null)
    {
        // in case of a deficiency,
        // we need to boot our general needs to be needed for the exception.
        Dependencies::loadBootstrapperNeedsForException();

        // in general we will use the exception class
        // in the store/config directory to make it possible
        // to change the user-based exceptions.
        $this->data['exception'] = $this->exception;

        //constant object as default
        $this->data['errType']              = 'Undefined';
        $this->data['errStrReal']           = $errStr;
        $this->data['errorClassNamespace']  = null;
        $this->data['errFile']              = $errFile;
        $this->data['errLine']              = $errLine;
        $this->data['errNo']                = $errNo;

        // catch exception via preg match
        // and then clear the Uncaught statement from inside.
        $this->getUncaughtProcess();

        $this->getStatusFromContext();

        if(is_array($meta=config('response.meta'))){

            //set as the success object is false
            $this->data['appExceptionSuccess']=[];
        }
        else{

            //set as the success object is false
            $this->data['appExceptionSuccess']=['success'=>(bool)false,'status'=>$this->data['status']];
        }

        //get lang message for exception
        $this->getLangMessageForException();

        if(property_exists(core(),'exceptiontrace')){

            $customExceptionTrace=core()->exceptiontrace;
            $this->data['errFile']=$customExceptionTrace['file'];
            $this->data['errLine']=$customExceptionTrace['line'];
        }

        $environment = $this->getEnvironmentStatus();

        $vendorDirectory = str_replace(root.''.DIRECTORY_SEPARATOR.'','',$this->data['errFile']);

        if(Str::startsWith($vendorDirectory,'vendor')
            && Str::startsWith($vendorDirectory,'vendor/restapix')===false)
        {
            $externalMessage = ($environment==="production") ?
                'An unexpected external error has occurred' :
                $this->data['errStrReal'];

            $appException=$this->getAppException($environment,$externalMessage);


            //Get or Set the HTTP response code
            http_response_code(500);
            $this->app->terminate('responseStatus');
            $this->app->register('responseStatus',500);


        }
        else{

            $appException=$this->getAppException($environment,$this->data['errStrReal']);

            //Get or Set the HTTP response code
            http_response_code($this->data['status']);
        }


        if($environment==="production"){

            $productionLogMessage = $this->getAppException('local',$this->data['errStrReal']);
            $this->app->register('productionLogMessage',core()->out->outputFormatter($productionLogMessage));
        }


        //set json app exception
        core()->routerResult=$appException;

        $restaOutHandle = null;

        if(!defined('responseApp')){

            $restaOutHandle=core()->out->handle();
        }

        if($restaOutHandle===null){

            //header set and symfony response call
            header('Content-type:application/json;charset=utf-8');

            echo json_encode(core()->out->outputFormatter($appException));
            exit();
        }
        else{
            echo $restaOutHandle;
            exit();
        }

    }

    /**
     * @param $environment
     * @return mixed
     */
    private function getAppException($environment,$message)
    {
        return $this->data['appExceptionSuccess']+$this->data['exception']::$environment(
            $this->data['errNo'],
            $message,
            $this->data['errFile'],
            $this->data['errLine'],
            $this->data['errType'],
            $this->data['lang']
        );
    }

    /**
     * @method fatalErrorShutdownHandler
     */
    public function fatalErrorShutdownHandler()
    {
        //get fatal error
        $last_error =error_get_last();

        $this->inStactTrace($last_error);

        if($last_error!==null){

            if(!defined('methodName')){

                define('methodName',null);
            }

            if(isset(core()->exceptionFile)){
                $last_error['file'] = core()->exceptionFile;
                $last_error['line'] = core()->exceptionLine;
            }

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
     * @param $error
     */
    public function inStactTrace($error)
    {
        if(isset(core()->urlComponent)){
            if(!preg_match('@'.core()->urlComponent['project'].'@',$error['file']) && !isset(core()->exceptionFile)){
                if(preg_match('@ in\s(.*?)\n@is',$error['message'],$result)){
                    $errorMessage = explode(":",$result[1]);
                    $this->app->register('exceptionFile',$errorMessage[0]);
                    $this->app->register('exceptionLine',$errorMessage[1]);
                }
            }
        }
    }

    /**
     * @return void|mixed
     */
    private function getLangMessageForException(){

        $clone = clone $this;

        if(property_exists(core(),'exceptionTranslate')){

            $langMessage=trans('exception.'.core()->exceptionTranslate);

            if(property_exists(core(),'exceptionTranslateParams')){

                if(count(core()->exceptionTranslateParams[core()->exceptionTranslate])){
                    foreach (core()->exceptionTranslateParams[core()->exceptionTranslate] as $key=>$value){
                        $langMessage=preg_replace('@\('.$key.'\)@is',$value,$langMessage);
                    }
                }
            }

            if($langMessage!==null){
                $this->data['errStrReal']=$langMessage;
            }
        }

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